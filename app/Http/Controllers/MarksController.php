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
use App\Models\SubjectAssignment;
use App\Services\GradeComputationService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MarksController extends Controller
{
    public function index()
    {
        $user = request()->user();
        $isRestrictedTeacher = $this->isRestrictedTeacher($user);

        $assignmentsQuery = SubjectAssignment::query()
            ->with('subject')
            ->orderByDesc('id');

        if ($isRestrictedTeacher) {
            $assignmentsQuery->where('teacher_id', $user->id);
        }

        $assignments = $assignmentsQuery->get();
        $classIds = $assignments->pluck('class_id')->unique()->values();
        $sectionIds = $assignments->whereNotNull('section_id')->pluck('section_id')->unique();
        $classWideIds = $assignments->whereNull('section_id')->pluck('class_id')->unique();
        $sectionIdsForClassWide = $classWideIds->isEmpty()
            ? collect([])
            : Section::query()
                ->forClasses($classWideIds)
                ->pluck('id');
        $allowedSectionIds = $sectionIds->merge($sectionIdsForClassWide)->unique()->values();

        return Inertia::render('Marks/Index', [
            'exams' => Exam::query()->orderByDesc('id')->get(),
            'classes' => SchoolClass::query()
                ->when($isRestrictedTeacher, fn ($q) => $q->whereIn('id', $classIds))
                ->orderBy('name')
                ->get(),
            'classLevels' => ClassLevel::query()->orderBy('name')->get(),
            'sections' => Section::query()
                ->when($isRestrictedTeacher, fn ($q) => $q->whereIn('id', $allowedSectionIds))
                ->orderBy('name')
                ->get(),
            'years' => AcademicYear::query()->orderByDesc('name')->get(),
            'terms' => Term::query()->orderBy('order')->get(),
            'subjects' => $assignments,
        ]);
    }

    public function listStudents(Request $request)
    {
        $data = $request->validate([
            'class_id' => ['required', 'integer', 'exists:classes,id'],
            'section_id' => ['nullable', 'integer', 'exists:sections,id'],
            'academic_year_id' => ['nullable', 'integer', 'exists:academic_years,id'],
        ]);

        $user = $request->user();
        if ($this->isRestrictedTeacher($user) && !$this->teacherHasClassAccess($user->id, $data['class_id'], $data['section_id'] ?? null)) {
            abort(403, 'You are not assigned to this class/section.');
        }

        $enrollments = StudentEnrollment::query()
            ->with(['student.user.profile'])
            ->where('class_id', $data['class_id'])
            ->when($data['section_id'] ?? null, fn ($q) => $q->where('section_id', $data['section_id']))
            ->when($data['academic_year_id'] ?? null, fn ($q) => $q->where('academic_year_id', $data['academic_year_id']))
            ->get();

        return response()->json($enrollments);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'exam_id' => ['required', 'integer', 'exists:exams,id'],
            'class_id' => ['required', 'integer', 'exists:classes,id'],
            'section_id' => ['nullable', 'integer', 'exists:sections,id'],
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

        $user = $request->user();
        if ($this->isRestrictedTeacher($user) && !$this->teacherHasSubjectAccess($user->id, $data['subject_id'], $data['class_id'], $data['section_id'] ?? null)) {
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
                    'section_id' => $data['section_id'],
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
            $service = new GradeComputationService();
            $service->computeForExamSubject(
                $data['exam_id'],
                $data['subject_id'],
                $data['class_id'],
                $data['section_id'] ?? null,
                $data['academic_year_id'] ?? null,
            );
        }

        return back();
    }

    private function isRestrictedTeacher($user): bool
    {
        if (!$user) {
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
