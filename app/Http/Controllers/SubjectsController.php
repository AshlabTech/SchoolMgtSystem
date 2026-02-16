<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\ClassLevel;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\SubjectAssignment;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SubjectsController extends Controller
{
    public function index()
    {
        return Inertia::render('Subjects/Index', [
            'subjects' => Subject::query()->orderBy('name')->get(),
            'assignments' => SubjectAssignment::query()
                ->with(['subject', 'schoolClass', 'section', 'teacher', 'academicYear'])
                ->orderByDesc('id')
                ->get(),
            'classes' => SchoolClass::query()->with('level')->orderBy('name')->get(),
            'classLevels' => ClassLevel::query()->orderBy('name')->get(),
            'sections' => Section::query()->orderBy('name')->get(),
            'years' => AcademicYear::query()->orderByDesc('name')->get(),
            'teachers' => User::role(['teacher', 'form_teacher'])->with('profile')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:subjects,name'],
            'code' => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        Subject::create($data);

        return back();
    }

    public function assign(Request $request)
    {
        $data = $request->validate([
            'subject_id' => ['required', 'integer', 'exists:subjects,id'],
            'class_id' => ['required', 'integer', 'exists:classes,id'],
            'section_id' => ['nullable', 'integer', 'exists:sections,id'],
            'teacher_id' => ['nullable', 'integer', 'exists:users,id'],
            'academic_year_id' => ['nullable', 'integer', 'exists:academic_years,id'],
        ]);

        SubjectAssignment::create([
            ...$data,
            'is_active' => true,
        ]);

        return back();
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();

        return back();
    }

    public function update(Request $request, Subject $subject)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:subjects,name,'.$subject->id],
            'code' => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $subject->update($data);

        return back();
    }

    public function destroyAssignment(SubjectAssignment $assignment)
    {
        $assignment->delete();

        return back();
    }

    public function updateAssignment(Request $request, SubjectAssignment $assignment)
    {
        $data = $request->validate([
            'subject_id' => ['required', 'integer', 'exists:subjects,id'],
            'class_id' => ['required', 'integer', 'exists:classes,id'],
            'section_id' => ['nullable', 'integer', 'exists:sections,id'],
            'teacher_id' => ['nullable', 'integer', 'exists:users,id'],
            'academic_year_id' => ['nullable', 'integer', 'exists:academic_years,id'],
        ]);

        $assignment->update($data);

        return back();
    }
}
