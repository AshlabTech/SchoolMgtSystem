<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Setting;
use App\Models\Term;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $academicYear = AcademicYear::query()
            ->where('is_current', true)
            ->first() ?? AcademicYear::query()->orderByDesc('name')->first();

        $term = Term::query()
            ->where('is_current', true)
            ->first();

        if (!$term && $academicYear) {
            $term = Term::query()
                ->where('academic_year_id', $academicYear->id)
                ->orderBy('order')
                ->first();
        }

        $defaults = [
            [
                'key' => 'set_current_academic_year',
                'label' => 'Current Academic Year',
                'group' => 'system',
                'type' => 'select',
                'options_source' => 'academic_years',
                'options_label' => 'name',
                'options_value' => 'id',
                'value' => $academicYear?->id,
                'description' => 'Active academic year for the system.',
                'is_locked' => true,
            ],
            [
                'key' => 'set_current_term',
                'label' => 'Current Term',
                'group' => 'system',
                'type' => 'select',
                'options_source' => 'terms',
                'options_label' => 'name',
                'options_value' => 'id',
                'value' => $term?->id,
                'description' => 'Active term for the system.',
                'is_locked' => true,
            ],
            [
                'key' => 'admission_number_format_template',
                'label' => 'Admission Number Template',
                'group' => 'admissions',
                'type' => 'text',
                'value' => 'ADM-{YYYY}-{NNNN}',
                'description' => 'Template for generated admission numbers. Use {YYYY} and {NNNN}.',
                'is_locked' => true,
            ],
            [
                'key' => 'auto_generate_admission_number',
                'label' => 'Auto Generate Admission Number',
                'group' => 'admissions',
                'type' => 'boolean',
                'value' => 1,
                'description' => 'Generate admission numbers automatically on student creation.',
                'is_locked' => true,
            ],
            [
                'key' => 'admission_number_assigned_until_first_tuition_fee_payment',
                'label' => 'Admission Number After First Tuition Payment',
                'group' => 'admissions',
                'type' => 'boolean',
                'value' => 0,
                'description' => 'Assign admission number only after first tuition fee payment.',
                'is_locked' => true,
            ],
        ];

        foreach ($defaults as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
