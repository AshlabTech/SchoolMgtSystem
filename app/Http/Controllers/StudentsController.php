<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\ClassLevel;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class StudentsController extends Controller
{
    public function index()
    {
        return Inertia::render('Students/Index', [
            'students' => Student::query()
                ->with(['user.profile', 'currentEnrollment.schoolClass', 'currentEnrollment.section'])
                ->orderBy('admission_no')
                ->get(),
            'classes' => SchoolClass::query()->orderBy('name')->get(),
            'classLevels' => ClassLevel::query()->orderBy('name')->get(),
            'years' => AcademicYear::query()->orderByDesc('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'other_name' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'username' => ['nullable', 'string', 'max:255', 'unique:users,username'],
            'admission_no' => ['required', 'string', 'max:50', 'unique:students,admission_no'],
            'class_id' => ['required', 'integer', 'exists:classes,id'],
            'section_id' => ['nullable', 'integer', 'exists:sections,id'],
            'academic_year_id' => ['required', 'integer', 'exists:academic_years,id'],
        ]);

        if (empty($data['section_id'])) {
            $data['section_id'] = Section::query()
                ->forClass($data['class_id'])
                ->orderBy('name')
                ->value('id');
        }

        $fullName = trim($data['first_name'].' '.$data['last_name']);
        $password = Str::password(10);

        $user = User::create([
            'name' => $fullName,
            'username' => $data['username'] ?? Str::slug($fullName).'-'.Str::random(4),
            'email' => $data['email'] ?? null,
            'password' => $password,
            'is_active' => true,
        ]);

        $user->assignRole('student');

        UserProfile::create([
            'user_id' => $user->id,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'other_name' => $data['other_name'] ?? null,
            'gender' => $data['gender'] ?? null,
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
        ]);

        $student = Student::create([
            'user_id' => $user->id,
            'admission_no' => $data['admission_no'],
            'joined_at' => now(),
            'status' => 'active',
        ]);

        StudentEnrollment::create([
            'student_id' => $student->id,
            'class_id' => $data['class_id'],
            'section_id' => $data['section_id'] ?? null,
            'academic_year_id' => $data['academic_year_id'],
            'is_current' => true,
            'status' => 'active',
            'enrolled_at' => now(),
        ]);

        return back();
    }

    public function destroy(Student $student)
    {
        $student->update(['status' => 'inactive']);
        $student->user?->update(['is_active' => false]);

        return back();
    }

    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'other_name' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email,'.$student->user_id],
            'username' => ['nullable', 'string', 'max:255', 'unique:users,username,'.$student->user_id],
            'admission_no' => ['required', 'string', 'max:50', 'unique:students,admission_no,'.$student->id],
            'class_id' => ['required', 'integer', 'exists:classes,id'],
            'section_id' => ['nullable', 'integer', 'exists:sections,id'],
            'academic_year_id' => ['required', 'integer', 'exists:academic_years,id'],
        ]);

        if (empty($data['section_id'])) {
            $data['section_id'] = Section::query()
                ->forClass($data['class_id'])
                ->orderBy('name')
                ->value('id');
        }

        $fullName = trim($data['first_name'].' '.$data['last_name']);

        $student->user?->update([
            'name' => $fullName,
            'email' => $data['email'] ?? null,
            'username' => $data['username'] ?? null,
        ]);

        $student->user?->profile()?->updateOrCreate(
            ['user_id' => $student->user_id],
            [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'other_name' => $data['other_name'] ?? null,
                'gender' => $data['gender'] ?? null,
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'phone' => $data['phone'] ?? null,
                'address' => $data['address'] ?? null,
            ]
        );

        $student->update([
            'admission_no' => $data['admission_no'],
        ]);

        $enrollment = $student->currentEnrollment;
        if ($enrollment) {
            $enrollment->update([
                'class_id' => $data['class_id'],
                'section_id' => $data['section_id'] ?? null,
                'academic_year_id' => $data['academic_year_id'],
            ]);
        } else {
            StudentEnrollment::create([
                'student_id' => $student->id,
                'class_id' => $data['class_id'],
                'section_id' => $data['section_id'] ?? null,
                'academic_year_id' => $data['academic_year_id'],
                'is_current' => true,
                'status' => 'active',
                'enrolled_at' => now(),
            ]);
        }

        return back();
    }
}
