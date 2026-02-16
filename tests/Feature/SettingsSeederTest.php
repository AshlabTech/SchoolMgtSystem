<?php

use App\Models\AcademicYear;
use App\Models\Setting;
use App\Models\Term;
use Database\Seeders\SettingsSeeder;

it('seeds all required Nigerian school settings', function () {
    AcademicYear::create([
        'name' => '2025/2026',
        'starts_at' => '2025-09-01',
        'ends_at' => '2026-07-31',
        'is_current' => true,
    ]);

    $academicYear = AcademicYear::first();
    Term::create([
        'academic_year_id' => $academicYear->id,
        'name' => 'First Term',
        'order' => 1,
        'starts_at' => '2025-09-01',
        'ends_at' => '2025-12-15',
        'is_current' => true,
    ]);

    (new SettingsSeeder())->run();

    $expectedKeys = [
        'set_current_academic_year',
        'set_current_term',
        'school_name',
        'school_address',
        'school_phone',
        'school_email',
        'school_motto',
        'school_type',
        'school_category',
        'principal_name',
        'admission_number_format_template',
        'auto_generate_admission_number',
        'admission_number_assigned_until_first_tuition_fee_payment',
        'ca_total_weight',
        'exam_weight',
        'number_of_ca_components',
        'auto_compute_grade',
        'auto_compute_subject_position',
        'show_position_on_result',
        'position_format',
        'result_approval_required',
        'auto_apply_result_comment',
        'next_term_resumption_date',
        'promotion_pass_mark',
        'number_of_terms',
    ];

    foreach ($expectedKeys as $key) {
        expect(Setting::where('key', $key)->exists())
            ->toBeTrue("Setting '$key' should exist");
    }
});

it('seeds settings into correct groups', function () {
    AcademicYear::create([
        'name' => '2025/2026',
        'starts_at' => '2025-09-01',
        'ends_at' => '2026-07-31',
        'is_current' => true,
    ]);

    $academicYear = AcademicYear::first();
    Term::create([
        'academic_year_id' => $academicYear->id,
        'name' => 'First Term',
        'order' => 1,
        'starts_at' => '2025-09-01',
        'ends_at' => '2025-12-15',
        'is_current' => true,
    ]);

    (new SettingsSeeder())->run();

    $groups = Setting::query()->pluck('group')->unique()->sort()->values()->all();

    expect($groups)->toContain('system')
        ->toContain('school_profile')
        ->toContain('admissions')
        ->toContain('grading')
        ->toContain('results')
        ->toContain('promotion');
});

it('seeds grading settings with Nigerian school defaults', function () {
    AcademicYear::create([
        'name' => '2025/2026',
        'starts_at' => '2025-09-01',
        'ends_at' => '2026-07-31',
        'is_current' => true,
    ]);

    $academicYear = AcademicYear::first();
    Term::create([
        'academic_year_id' => $academicYear->id,
        'name' => 'First Term',
        'order' => 1,
        'starts_at' => '2025-09-01',
        'ends_at' => '2025-12-15',
        'is_current' => true,
    ]);

    (new SettingsSeeder())->run();

    expect(Setting::where('key', 'ca_total_weight')->value('value'))->toBe('40')
        ->and(Setting::where('key', 'exam_weight')->value('value'))->toBe('60')
        ->and(Setting::where('key', 'number_of_ca_components')->value('value'))->toBe('2')
        ->and(Setting::where('key', 'number_of_terms')->value('value'))->toBe('3');
});
