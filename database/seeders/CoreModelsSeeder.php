<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\ClassLevel;
use App\Models\Exam;
use App\Models\Grade;
use App\Models\InvoiceType;
use App\Models\PaymentCategory;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Term;
use Illuminate\Database\Seeder;

class CoreModelsSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            'Mathematics',
            'English Language',
            'Basic Science',
            'Civic Education',
            'Computer Science',
            'Social Studies',
            'Agricultural Science',
            'Business Studies',
        ] as $subjectName) {
            Subject::firstOrCreate(
                ['name' => $subjectName],
                ['code' => str($subjectName)->upper()->replace(' ', '_')->value(), 'is_active' => true]
            );
        }

        $gradingBands = [
            ['name' => 'A', 'mark_from' => 70, 'mark_to' => 100, 'remark' => 'Excellent'],
            ['name' => 'B', 'mark_from' => 60, 'mark_to' => 69, 'remark' => 'Very Good'],
            ['name' => 'C', 'mark_from' => 50, 'mark_to' => 59, 'remark' => 'Good'],
            ['name' => 'D', 'mark_from' => 40, 'mark_to' => 49, 'remark' => 'Fair'],
            ['name' => 'F', 'mark_from' => 0, 'mark_to' => 39, 'remark' => 'Fail'],
        ];

        foreach (ClassLevel::query()->pluck('id') as $classLevelId) {
            foreach ($gradingBands as $grade) {
                Grade::firstOrCreate(
                    ['class_level_id' => $classLevelId, 'name' => $grade['name'], 'remark' => $grade['remark']],
                    ['mark_from' => $grade['mark_from'], 'mark_to' => $grade['mark_to']]
                );
            }
        }

        $academicYear = AcademicYear::query()->where('is_current', true)->first() ?? AcademicYear::query()->latest('id')->first();
        $term = Term::query()->where('is_current', true)->first() ?? Term::query()->oldest('order')->first();

        if ($academicYear && $term) {
            Exam::firstOrCreate(
                ['name' => 'First Term Examination', 'academic_year_id' => $academicYear->id, 'term_id' => $term->id],
                ['starts_at' => now()->addWeeks(2), 'ends_at' => now()->addWeeks(3), 'is_published' => false]
            );
        }

        $tuitionCategory = PaymentCategory::query()->firstOrCreate(['name' => 'Tuition']);
        $section = Section::query()->where('name', 'Secondary')->first() ?? Section::query()->first();

        if ($academicYear && $term) {
            InvoiceType::firstOrCreate(
                ['name' => 'Termly Tuition', 'academic_year_id' => $academicYear->id, 'term_id' => $term->id],
                [
                    'amount' => 120000,
                    'payment_category_id' => $tuitionCategory->id,
                    'section_id' => $section?->id,
                ]
            );
        }
    }
}
