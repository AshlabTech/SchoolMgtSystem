<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\ClassLevel;
use App\Models\Promotion;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentEnrollment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PromotionsController extends Controller
{
    public function index()
    {
        $user = request()->user();
        $isFormTeacher = $user && $user->hasRole('form_teacher') && !$user->hasAnyRole(['admin', 'super_admin']);
        $sectionIds = collect([]);
        $classIds = collect([]);
        if ($isFormTeacher) {
            $sections = Section::with('schoolClasses')->where('teacher_id', $user->id)->get();
            $sectionIds = $sections->pluck('id');
            $classIds = $sections->flatMap(function ($section) {
                return $section->schoolClasses->pluck('id');
            })->unique();
        }

        return Inertia::render('Promotions/Index', [
            'students' => Student::with(['user', 'currentEnrollment.schoolClass', 'currentEnrollment.section'])
                ->when($isFormTeacher, fn ($q) => $q->whereHas('currentEnrollment', fn ($enroll) => $enroll->whereIn('section_id', $sectionIds)))
                ->orderByDesc('created_at')
                ->get(),
            'classes' => SchoolClass::orderBy('name')
                ->when($isFormTeacher, fn ($q) => $q->whereIn('id', $classIds))
                ->get(),
            'classLevels' => ClassLevel::orderBy('name')->get(),
            'sections' => Section::orderBy('name')
                ->when($isFormTeacher, fn ($q) => $q->whereIn('id', $sectionIds))
                ->get(),
            'academicYears' => AcademicYear::orderByDesc('name')->get(),
            'promotions' => Promotion::with(['student.user', 'fromClass', 'toClass', 'fromSection', 'toSection'])
                ->when($isFormTeacher, fn ($q) => $q->whereIn('from_section_id', $sectionIds))
                ->orderByDesc('created_at')
                ->take(100)
                ->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'to_class_id' => ['required', 'exists:classes,id'],
            'to_section_id' => ['nullable', 'exists:sections,id'],
            'to_academic_year_id' => ['required', 'exists:academic_years,id'],
            'is_graduated' => ['nullable', 'boolean'],
        ]);

        $student = Student::with('currentEnrollment.section')->findOrFail($data['student_id']);
        $currentEnrollment = $student->currentEnrollment;

        $user = $request->user();
        if ($user && $user->hasRole('form_teacher') && !$user->hasAnyRole(['admin', 'super_admin'])) {
            $allowedSection = $currentEnrollment?->section?->teacher_id === $user->id;
            if (!$allowedSection) {
                abort(403, 'You can only promote students in your class.');
            }
        }

        $promotionData = [
            'student_id' => $student->id,
            'from_enrollment_id' => $currentEnrollment?->id,
            'from_class_id' => $currentEnrollment?->class_id,
            'from_section_id' => $currentEnrollment?->section_id,
            'from_academic_year_id' => $currentEnrollment?->academic_year_id,
            'to_class_id' => $data['to_class_id'],
            'to_section_id' => $data['to_section_id'] ?? null,
            'to_academic_year_id' => $data['to_academic_year_id'],
            'is_graduated' => (bool) ($data['is_graduated'] ?? false),
            'status' => ($data['is_graduated'] ?? false) ? 'graduated' : 'promoted',
        ];

        if ($currentEnrollment) {
            $currentEnrollment->update(['is_current' => false, 'status' => 'promoted']);
        }

        if ($promotionData['is_graduated']) {
            $student->update([
                'is_graduated' => true,
                'graduated_at' => Carbon::now(),
            ]);
        } else {
            $newEnrollment = StudentEnrollment::create([
                'student_id' => $student->id,
                'class_id' => $data['to_class_id'],
                'section_id' => $data['to_section_id'] ?? null,
                'academic_year_id' => $data['to_academic_year_id'],
                'term_id' => null,
                'status' => 'active',
                'is_current' => true,
                'enrolled_at' => Carbon::now(),
            ]);
            $promotionData['to_enrollment_id'] = $newEnrollment->id;
        }

        Promotion::create($promotionData);

        return back();
    }

    public function reset(Promotion $promotion)
    {
        if ($promotion->toEnrollment) {
            $promotion->toEnrollment->delete();
        }

        if ($promotion->fromEnrollment) {
            $promotion->fromEnrollment->update(['is_current' => true, 'status' => 'active']);
        }

        $promotion->update(['status' => 'reset']);

        $promotion->student?->update([
            'is_graduated' => false,
            'graduated_at' => null,
        ]);

        return back();
    }
}
