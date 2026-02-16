<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Exam;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Skill;
use App\Models\SkillScore;
use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SkillScoresController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if ($user && $user->hasRole('teacher') && !$user->hasRole('form_teacher') && !$user->hasAnyRole(['admin', 'super_admin'])) {
            abort(403, 'Teachers cannot view skill scores without form teacher access.');
        }

        $isFormTeacher = $user && $user->hasRole('form_teacher') && !$user->hasAnyRole(['admin', 'super_admin']);
        $sectionIds = collect([]);
        if ($isFormTeacher) {
            $sectionIds = Section::where('teacher_id', $user->id)->pluck('id');
        }

        $classes = SchoolClass::with('classLevel')->orderBy('name')->get();
        $sections = Section::with('schoolClass')->orderBy('name')->get();
        $exams = Exam::orderBy('name')->get();
        $years = AcademicYear::orderByDesc('name')->get();

        $students = collect([]);
        $skills = collect([]);
        $skillScores = collect([]);
        $selected = null;

        if ($request->filled('class_id') && $request->filled('exam_id')) {
            $selected = [
                'class_id' => (int) $request->input('class_id'),
                'section_id' => $request->input('section_id') ? (int) $request->input('section_id') : null,
                'exam_id' => (int) $request->input('exam_id'),
                'academic_year_id' => $request->input('academic_year_id') ? (int) $request->input('academic_year_id') : null,
            ];

            $studentsQuery = Student::with('user.profile')
                ->whereHas('currentEnrollment', function ($q) use ($selected) {
                    $q->where('class_id', $selected['class_id']);
                    if ($selected['section_id']) {
                        $q->where('section_id', $selected['section_id']);
                    }
                });

            if ($isFormTeacher) {
                $studentsQuery->whereHas('currentEnrollment', fn ($enroll) => $enroll->whereIn('section_id', $sectionIds));
            }

            $students = $studentsQuery->orderBy('created_at')->get();

            $class = SchoolClass::with('classLevel')->find($selected['class_id']);
            $skills = Skill::query()
                ->where(function ($q) use ($class) {
                    $q->whereNull('class_level_id')
                        ->orWhere('class_level_id', $class->class_level_id);
                })
                ->orderBy('skill_type')
                ->orderBy('name')
                ->get();

            $skillScores = SkillScore::query()
                ->with(['skill', 'student'])
                ->where('class_id', $selected['class_id'])
                ->where('exam_id', $selected['exam_id'])
                ->when($selected['section_id'], fn ($q) => $q->where('section_id', $selected['section_id']))
                ->when($selected['academic_year_id'], fn ($q) => $q->where('academic_year_id', $selected['academic_year_id']))
                ->get();
        }

        return Inertia::render('SkillScores/Index', [
            'classes' => $classes,
            'sections' => $sections,
            'exams' => $exams,
            'years' => $years,
            'students' => $students,
            'skills' => $skills,
            'skillScores' => $skillScores,
            'selected' => $selected,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'skill_id' => ['required', 'integer', 'exists:skills,id'],
            'exam_id' => ['required', 'integer', 'exists:exams,id'],
            'class_id' => ['required', 'integer', 'exists:classes,id'],
            'section_id' => ['nullable', 'integer', 'exists:sections,id'],
            'academic_year_id' => ['nullable', 'integer', 'exists:academic_years,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:500'],
        ]);

        SkillScore::updateOrCreate(
            [
                'student_id' => $data['student_id'],
                'skill_id' => $data['skill_id'],
                'exam_id' => $data['exam_id'],
                'academic_year_id' => $data['academic_year_id'] ?? null,
            ],
            $data
        );

        return back();
    }

    public function bulkStore(Request $request)
    {
        $data = $request->validate([
            'scores' => ['required', 'array'],
            'scores.*.student_id' => ['required', 'integer', 'exists:students,id'],
            'scores.*.skill_id' => ['required', 'integer', 'exists:skills,id'],
            'scores.*.exam_id' => ['required', 'integer', 'exists:exams,id'],
            'scores.*.class_id' => ['required', 'integer', 'exists:classes,id'],
            'scores.*.section_id' => ['nullable', 'integer', 'exists:sections,id'],
            'scores.*.academic_year_id' => ['nullable', 'integer', 'exists:academic_years,id'],
            'scores.*.rating' => ['required', 'integer', 'min:1', 'max:5'],
            'scores.*.comment' => ['nullable', 'string', 'max:500'],
        ]);

        foreach ($data['scores'] as $scoreData) {
            SkillScore::updateOrCreate(
                [
                    'student_id' => $scoreData['student_id'],
                    'skill_id' => $scoreData['skill_id'],
                    'exam_id' => $scoreData['exam_id'],
                    'academic_year_id' => $scoreData['academic_year_id'] ?? null,
                ],
                $scoreData
            );
        }

        return back();
    }
}
