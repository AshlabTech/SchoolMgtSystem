<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\ClassLevel;
use App\Models\Exam;
use App\Models\Mark;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Setting;
use App\Models\StudentEnrollment;
use App\Models\Subject;
use App\Models\SubjectAssignment;
use App\Models\Term;
use App\Services\GradeComputationService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MarksController extends Controller
{
    public function index()
    {
        $user = request()->user();
        $isRestrictedTeacher = $this->isRestrictedTeacher($user);

        $assignments = $isRestrictedTeacher
            ? SubjectAssignment::query()
                ->with('subject')
                ->where('teacher_id', $user->id)
                ->orderByDesc('id')
                ->get()
            : Subject::query()
                ->orderBy('name')
                ->get()
                ->map(fn (Subject $subject) => [
                    'subject_id' => $subject->id,
                    'subject' => $subject,
                ]);
        $classIds = collect([]);
        $allowedSectionIds = collect([]);
        if ($isRestrictedTeacher) {
            $classIds = $assignments->pluck('class_id')->unique()->values();
            $sectionIds = $assignments->whereNotNull('section_id')->pluck('section_id')->unique();
            $classWideIds = $assignments->whereNull('section_id')->pluck('class_id')->unique();
            $sectionIdsForClassWide = $classWideIds->isEmpty()
                ? collect([])
                : Section::query()
                    ->forClasses($classWideIds)
                    ->pluck('id');
            $allowedSectionIds = $sectionIds->merge($sectionIdsForClassWide)->unique()->values();
        }

        $currentYear = AcademicYear::query()->where('is_current', true)->first();
        $currentTerm = Term::query()->where('is_current', true)->first();

        // Get sections with their CA component counts
        $sections = Section::query()
            ->when($isRestrictedTeacher, fn ($q) => $q->whereIn('id', $allowedSectionIds))
            ->orderBy('name')
            ->get()
            ->map(function ($section) {
                return [
                    'id' => $section->id,
                    'class_id' => $section->class_id,
                    'teacher_id' => $section->teacher_id,
                    'name' => $section->name,
                    'is_active' => $section->is_active,
                    'number_of_ca_components' => $section->getNumberOfCaComponents(),
                ];
            });

        return Inertia::render('Marks/Index', [
            'exams' => Exam::query()->orderByDesc('id')->get(),
            'classes' => SchoolClass::query()
                ->when($isRestrictedTeacher, fn ($q) => $q->whereIn('id', $classIds))
                ->orderBy('name')
                ->get(),
            'classLevels' => ClassLevel::query()->orderBy('name')->get(),
            'sections' => $sections,
            'years' => AcademicYear::query()->orderByDesc('name')->get(),
            'terms' => Term::query()->orderBy('order')->get(),
            'subjects' => $assignments,
            'numberOfCaComponents' => (int) (Setting::where('key', 'number_of_ca_components')->value('value') ?? 2),
            'currentAcademicYearId' => $currentYear?->id,
            'currentTermId' => $currentTerm?->id,
        ]);
    }

    public function listStudents(Request $request)
    {
        $data = $request->validate([
            'class_id' => ['required', 'integer', 'exists:classes,id'],
            'term_id' => ['nullable', 'integer', 'exists:terms,id'],
            'academic_year_id' => ['nullable', 'integer', 'exists:academic_years,id'],
            'exam_id' => ['nullable', 'integer', 'exists:exams,id'],
            'subject_id' => ['nullable', 'integer', 'exists:subjects,id'],
        ]);

        if (! empty($data['exam_id']) && ! empty($data['term_id'])) {
            $examBelongsToTerm = Exam::query()
                ->whereKey($data['exam_id'])
                ->where('term_id', $data['term_id'])
                ->exists();

            if (! $examBelongsToTerm) {
                return response()->json([
                    'students' => [],
                    'ca_components' => (int) (Setting::where('key', 'number_of_ca_components')->value('value') ?? 2),
                    'message' => 'Selected exam does not belong to the selected term.',
                ], 422);
            }
        }

        $user = $request->user();
        if ($this->isRestrictedTeacher($user) && ! $this->teacherHasClassAccess($user->id, $data['class_id'], null)) {
            abort(403, 'You are not assigned to this class.');
        }

        $enrollments = StudentEnrollment::query()
            ->with(['student.user.profile', 'section'])
            ->where('class_id', $data['class_id'])
            ->when($data['academic_year_id'] ?? null, fn ($q) => $q->where('academic_year_id', $data['academic_year_id']))
            ->get();

        // Load existing marks if exam_id and subject_id are provided
        $marks = [];
        if (isset($data['exam_id']) && isset($data['subject_id'])) {
            $existingMarks = Mark::query()
                ->where('exam_id', $data['exam_id'])
                ->where('subject_id', $data['subject_id'])
                ->where('class_id', $data['class_id'])
                ->when($data['academic_year_id'] ?? null, fn ($q) => $q->where('academic_year_id', $data['academic_year_id']))
                ->when(
                    $data['term_id'] ?? null,
                    fn ($q, $termId) => $q->whereHas('exam', fn ($exam) => $exam->where('term_id', $termId))
                )
                ->get()
                ->keyBy('student_id');

            $marks = $existingMarks;
        }

        // Group enrollments by section to determine CA components
        $sectionCaComponents = [];
        foreach ($enrollments as $enrollment) {
            if ($enrollment->section_id && !isset($sectionCaComponents[$enrollment->section_id])) {
                $section = $enrollment->section ?? Section::find($enrollment->section_id);
                $sectionCaComponents[$enrollment->section_id] = $section ? $section->getNumberOfCaComponents() : null;
            }
        }

        // Get the most common CA component count (or global default)
        $caComponentsCount = !empty($sectionCaComponents) 
            ? max(array_values($sectionCaComponents)) 
            : (int) (Setting::where('key', 'number_of_ca_components')->value('value') ?? 2);

        // Merge enrollment data with marks
        $result = $enrollments->map(function ($enrollment) use ($marks, $sectionCaComponents) {
            $mark = $marks[$enrollment->student_id] ?? null;
            $sectionId = $enrollment->section_id;
            $studentCaComponents = $sectionId && isset($sectionCaComponents[$sectionId]) 
                ? $sectionCaComponents[$sectionId] 
                : null;

            return [
                'student_id' => $enrollment->student_id,
                'student' => $enrollment->student,
                'section_id' => $sectionId,
                'section_ca_components' => $studentCaComponents,
                't1' => $mark?->t1 ?? null,
                't2' => $mark?->t2 ?? null,
                't3' => $mark?->t3 ?? null,
                't4' => $mark?->t4 ?? null,
                'exm' => $mark?->exm ?? null,
            ];
        });

        return response()->json([
            'students' => $result,
            'ca_components' => $caComponentsCount,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'exam_id' => ['required', 'integer', 'exists:exams,id'],
            'class_id' => ['required', 'integer', 'exists:classes,id'],
            'term_id' => ['nullable', 'integer', 'exists:terms,id'],
            'academic_year_id' => ['nullable', 'integer', 'exists:academic_years,id'],
            'subject_id' => ['required', 'integer', 'exists:subjects,id'],
            'entries' => ['required', 'array'],
            'entries.*.student_id' => ['required', 'integer', 'exists:students,id'],
            'entries.*.t1' => ['nullable', 'integer'],
            'entries.*.t2' => ['nullable', 'integer'],
            'entries.*.t3' => ['nullable', 'integer'],
            'entries.*.t4' => ['nullable', 'integer'],
            'entries.*.exm' => ['nullable', 'integer'],
        ]);

        if (! empty($data['term_id'])) {
            $examBelongsToTerm = Exam::query()
                ->whereKey($data['exam_id'])
                ->where('term_id', $data['term_id'])
                ->exists();

            if (! $examBelongsToTerm) {
                return back()->withErrors([
                    'term_id' => 'Selected exam does not belong to the selected term.',
                ]);
            }
        }

        $user = $request->user();
        if ($this->isRestrictedTeacher($user) && ! $this->teacherHasSubjectAccess($user->id, $data['subject_id'], $data['class_id'], null)) {
            abort(403, 'You are not assigned to this subject or class.');
        }

        foreach ($data['entries'] as $entry) {
            Mark::updateOrCreate(
                [
                    'student_id' => $entry['student_id'],
                    'subject_id' => $data['subject_id'],
                    'exam_id' => $data['exam_id'],
                    'academic_year_id' => $data['academic_year_id'],
                ],
                [
                    'class_id' => $data['class_id'],
                    'section_id' => null, // No longer using section_id
                    't1' => $entry['t1'] ?? null,
                    't2' => $entry['t2'] ?? null,
                    't3' => $entry['t3'] ?? null,
                    't4' => $entry['t4'] ?? null,
                    'exm' => $entry['exm'] ?? null,
                ]
            );
        }

        $autoCompute = (bool) Setting::where('key', 'auto_compute_grade')->value('value');

        if ($autoCompute) {
            $service = new GradeComputationService;
            $service->computeForExamSubject(
                $data['exam_id'],
                $data['subject_id'],
                $data['class_id'],
                null, // section_id is null
                $data['academic_year_id'] ?? null,
            );
        }

        return back();
    }

    private function isRestrictedTeacher($user): bool
    {
        if (! $user) {
            return false;
        }

        if ($user->hasAnyRole(['super_admin', 'admin'])) {
            return false;
        }

        return $user->hasAnyRole(['teacher', 'form_teacher']);
    }

    private function teacherHasClassAccess(int $userId, int $classId, ?int $sectionId): bool
    {
        return SubjectAssignment::query()
            ->where('teacher_id', $userId)
            ->where('class_id', $classId)
            ->where(function ($query) use ($sectionId) {
                $query->whereNull('section_id');
                if ($sectionId) {
                    $query->orWhere('section_id', $sectionId);
                }
            })
            ->exists();
    }

    private function teacherHasSubjectAccess(int $userId, int $subjectId, int $classId, ?int $sectionId): bool
    {
        return SubjectAssignment::query()
            ->where('teacher_id', $userId)
            ->where('subject_id', $subjectId)
            ->where('class_id', $classId)
            ->where(function ($query) use ($sectionId) {
                $query->whereNull('section_id');
                if ($sectionId) {
                    $query->orWhere('section_id', $sectionId);
                }
            })
            ->exists();
    }
}
