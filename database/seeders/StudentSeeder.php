<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Models\Term;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $academicYear = AcademicYear::query()->where('is_current', true)->first() ?? AcademicYear::query()->latest('id')->first();
        if (!$academicYear) {
            return;
        }

        $term = Term::query()
            ->where('academic_year_id', $academicYear->id)
            ->where('is_current', true)
            ->first() ?? Term::query()->where('academic_year_id', $academicYear->id)->orderBy('order')->first();

        $classNames = ['Primary 1', 'Primary 3', 'JSS 1'];
        $classes = SchoolClass::query()->whereIn('name', $classNames)->get()->keyBy('name');
        $seedData = [
            ['first_name' => 'Amina', 'last_name' => 'Yusuf', 'class' => 'Primary 1'],
            ['first_name' => 'Daniel', 'last_name' => 'Okafor', 'class' => 'Primary 1'],
            ['first_name' => 'Grace', 'last_name' => 'Adeyemi', 'class' => 'Primary 3'],
            ['first_name' => 'Musa', 'last_name' => 'Bello', 'class' => 'Primary 3'],
            ['first_name' => 'Chioma', 'last_name' => 'Nwosu', 'class' => 'JSS 1'],
            ['first_name' => 'Ibrahim', 'last_name' => 'Sani', 'class' => 'JSS 1'],
        ];

        foreach ($seedData as $index => $row) {
            $class = $classes->get($row['class']);
            if (!$class) {
                continue;
            }

            $username = 'std'.$academicYear->id.str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT);
            $user = User::firstOrCreate(
                ['username' => $username],
                [
                    'name' => $row['first_name'].' '.$row['last_name'],
                    'email' => $username.'@school.local',
                    'password' => Hash::make('password'),
                    'is_active' => true,
                ]
            );
            $user->assignRole('student');

            UserProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'first_name' => $row['first_name'],
                    'last_name' => $row['last_name'],
                    'gender' => $index % 2 === 0 ? 'female' : 'male',
                ]
            );

            $student = Student::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'admission_no' => 'ADM-'.$academicYear->id.'-'.str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT),
                    'joined_at' => now()->startOfYear(),
                    'status' => 'active',
                ]
            );

            $sectionId = Section::query()->forClass($class->id)->value('id');

            StudentEnrollment::updateOrCreate(
                ['student_id' => $student->id, 'academic_year_id' => $academicYear->id],
                [
                    'class_id' => $class->id,
                    'section_id' => $sectionId,
                    'term_id' => $term?->id,
                    'status' => 'active',
                    'is_current' => true,
                    'enrolled_at' => now()->startOfYear(),
                ]
            );
        }
    }
}
