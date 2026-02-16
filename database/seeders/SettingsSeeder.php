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
            // ── System ──────────────────────────────────────────────
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

            // ── School Profile ───────────────────────────────────────
            [
                'key' => 'school_name',
                'label' => 'School Name',
                'group' => 'school_profile',
                'type' => 'text',
                'value' => '',
                'description' => 'Official name of the school.',
                'is_locked' => true,
            ],
            [
                'key' => 'school_address',
                'label' => 'School Address',
                'group' => 'school_profile',
                'type' => 'text',
                'value' => '',
                'description' => 'Physical address of the school.',
                'is_locked' => true,
            ],
            [
                'key' => 'school_phone',
                'label' => 'School Phone',
                'group' => 'school_profile',
                'type' => 'text',
                'value' => '',
                'description' => 'Contact phone number for the school.',
                'is_locked' => true,
            ],
            [
                'key' => 'school_email',
                'label' => 'School Email',
                'group' => 'school_profile',
                'type' => 'text',
                'value' => '',
                'description' => 'Contact email address for the school.',
                'is_locked' => true,
            ],
            [
                'key' => 'school_motto',
                'label' => 'School Motto',
                'group' => 'school_profile',
                'type' => 'text',
                'value' => '',
                'description' => 'The school motto displayed on report cards.',
                'is_locked' => true,
            ],
            [
                'key' => 'school_type',
                'label' => 'School Type',
                'group' => 'school_profile',
                'type' => 'select',
                'options' => [
                    ['label' => 'Primary', 'value' => 'primary'],
                    ['label' => 'Secondary', 'value' => 'secondary'],
                    ['label' => 'Combined (Primary & Secondary)', 'value' => 'combined'],
                ],
                'value' => 'combined',
                'description' => 'Whether this is a primary school, secondary school, or combined.',
                'is_locked' => true,
            ],
            [
                'key' => 'school_category',
                'label' => 'School Category',
                'group' => 'school_profile',
                'type' => 'select',
                'options' => [
                    ['label' => 'Private', 'value' => 'private'],
                    ['label' => 'Public/Government', 'value' => 'public'],
                    ['label' => 'Mission/Faith-based', 'value' => 'mission'],
                ],
                'value' => 'private',
                'description' => 'Category of the school (private, public or mission-owned).',
                'is_locked' => true,
            ],
            [
                'key' => 'principal_name',
                'label' => 'Principal / Head Teacher Name',
                'group' => 'school_profile',
                'type' => 'text',
                'value' => '',
                'description' => 'Name of the principal or head teacher for report cards.',
                'is_locked' => true,
            ],

            // ── Admissions ───────────────────────────────────────────
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

            // ── Grading & Assessment ─────────────────────────────────
            [
                'key' => 'ca_total_weight',
                'label' => 'CA Total Weight (%)',
                'group' => 'grading',
                'type' => 'number',
                'value' => 40,
                'description' => 'Maximum percentage allocated to continuous assessment (e.g. 40 means CA is out of 40).',
                'is_locked' => true,
            ],
            [
                'key' => 'exam_weight',
                'label' => 'Exam Weight (%)',
                'group' => 'grading',
                'type' => 'number',
                'value' => 60,
                'description' => 'Maximum percentage allocated to the exam score (e.g. 60 means exam is out of 60).',
                'is_locked' => true,
            ],
            [
                'key' => 'number_of_ca_components',
                'label' => 'Number of CA Components',
                'group' => 'grading',
                'type' => 'select',
                'options' => [
                    ['label' => '1 (T1 only)', 'value' => '1'],
                    ['label' => '2 (T1 & T2)', 'value' => '2'],
                    ['label' => '3 (T1, T2 & T3)', 'value' => '3'],
                    ['label' => '4 (T1, T2, T3 & T4)', 'value' => '4'],
                ],
                'value' => '2',
                'description' => 'How many continuous assessment tests are recorded per subject per term.',
                'is_locked' => true,
            ],
            [
                'key' => 'auto_compute_grade',
                'label' => 'Auto-Compute Grade on Mark Entry',
                'group' => 'grading',
                'type' => 'boolean',
                'value' => 1,
                'description' => 'Automatically assign grade based on total score when marks are saved.',
                'is_locked' => true,
            ],
            [
                'key' => 'auto_compute_subject_position',
                'label' => 'Auto-Compute Subject Position',
                'group' => 'grading',
                'type' => 'boolean',
                'value' => 1,
                'description' => 'Automatically rank students per subject when marks are saved.',
                'is_locked' => true,
            ],

            // ── Results & Report Cards ───────────────────────────────
            [
                'key' => 'show_position_on_result',
                'label' => 'Show Position on Result',
                'group' => 'results',
                'type' => 'boolean',
                'value' => 1,
                'description' => 'Display class position on student report cards.',
                'is_locked' => true,
            ],
            [
                'key' => 'position_format',
                'label' => 'Position Format',
                'group' => 'results',
                'type' => 'select',
                'options' => [
                    ['label' => 'Numeric (1, 2, 3)', 'value' => 'numeric'],
                    ['label' => 'Ordinal (1st, 2nd, 3rd)', 'value' => 'ordinal'],
                ],
                'value' => 'ordinal',
                'description' => 'How student positions are displayed on report cards.',
                'is_locked' => true,
            ],
            [
                'key' => 'result_approval_required',
                'label' => 'Result Approval Required',
                'group' => 'results',
                'type' => 'boolean',
                'value' => 0,
                'description' => 'Require admin approval before results are visible to students/parents.',
                'is_locked' => true,
            ],
            [
                'key' => 'auto_apply_result_comment',
                'label' => 'Auto Apply Result Comment',
                'group' => 'results',
                'type' => 'boolean',
                'value' => 0,
                'description' => 'Automatically apply the default predefined result comment during result computation.',
                'is_locked' => true,
            ],
            [
                'key' => 'next_term_resumption_date',
                'label' => 'Next Term Resumption Date',
                'group' => 'results',
                'type' => 'date',
                'value' => null,
                'description' => 'Date shown on report cards for when the next term begins.',
                'is_locked' => true,
            ],

            // ── Promotion ────────────────────────────────────────────
            [
                'key' => 'promotion_pass_mark',
                'label' => 'Promotion Pass Mark (%)',
                'group' => 'promotion',
                'type' => 'number',
                'value' => 50,
                'description' => 'Minimum average percentage required for a student to be promoted.',
                'is_locked' => true,
            ],
            [
                'key' => 'number_of_terms',
                'label' => 'Number of Terms per Session',
                'group' => 'promotion',
                'type' => 'select',
                'options' => [
                    ['label' => '2 Terms', 'value' => '2'],
                    ['label' => '3 Terms', 'value' => '3'],
                ],
                'value' => '3',
                'description' => 'How many terms in each academic session (most Nigerian schools use 3).',
                'is_locked' => true,
            ],
        ];

        foreach ($defaults as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
