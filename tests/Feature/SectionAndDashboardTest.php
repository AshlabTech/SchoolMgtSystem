<?php

use App\Models\AcademicYear;
use App\Models\ClassLevel;
use App\Models\Exam;
use App\Models\FeeRecord;
use App\Models\InvoiceType;
use App\Models\PaymentCategory;
use App\Models\Receipt;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\StaffProfile;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Models\Term;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Middleware\PermissionMiddleware;

it('stores and updates section with a single class', function () {
    $this->withoutMiddleware(PermissionMiddleware::class);

    $user = User::factory()->create();
    $this->actingAs($user);

    $level = ClassLevel::create(['name' => 'Junior Secondary', 'code' => 'JS']);
    $classA = SchoolClass::create(['class_level_id' => $level->id, 'name' => 'JSS 1']);
    $classB = SchoolClass::create(['class_level_id' => $level->id, 'name' => 'JSS 2']);
    $teacher = User::factory()->create();

    $this->from('/academics')->post(route('academics.sections.store'), [
        'class_id' => $classA->id,
        'name' => 'A',
        'teacher_id' => $teacher->id,
    ])->assertRedirect('/academics');

    $section = Section::query()->firstOrFail();
    expect($section->class_id)->toBe($classA->id);

    $this->from('/academics')->put(route('academics.sections.update', $section), [
        'class_id' => $classB->id,
        'name' => 'B',
        'teacher_id' => $teacher->id,
    ])->assertRedirect('/academics');

    expect($section->fresh()->class_id)->toBe($classB->id)
        ->and($section->fresh()->name)->toBe('B');
});

it('loads dashboard with computed data instead of static values', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $staffUser = User::factory()->create(['is_active' => true]);
    StaffProfile::create(['user_id' => $staffUser->id, 'employee_code' => 'EMP-1001']);

    $studentUser = User::factory()->create();
    $student = Student::create([
        'user_id' => $studentUser->id,
        'admission_no' => 'ADM-1001',
        'joined_at' => now(),
        'is_graduated' => false,
        'status' => 'active',
    ]);

    $year = AcademicYear::create(['name' => '2025/2026']);
    $term = Term::create(['academic_year_id' => $year->id, 'name' => 'First Term', 'order' => 1]);
    $level = ClassLevel::create(['name' => 'Senior Secondary', 'code' => 'SS']);
    $class = SchoolClass::create(['class_level_id' => $level->id, 'name' => 'SS1']);
    $section = Section::create(['class_id' => $class->id, 'name' => 'A']);
    StudentEnrollment::create([
        'student_id' => $student->id,
        'class_id' => $class->id,
        'section_id' => $section->id,
        'academic_year_id' => $year->id,
        'is_current' => true,
    ]);

    $category = PaymentCategory::create(['name' => 'Tuition']);
    $invoiceType = InvoiceType::create([
        'name' => 'Termly Fee',
        'amount' => 120000,
        'payment_category_id' => $category->id,
        'class_id' => $class->id,
    ]);
    $record = FeeRecord::create([
        'invoice_type_id' => $invoiceType->id,
        'student_id' => $student->id,
        'reference' => 'REF-1001',
        'amount_paid' => 30000,
        'balance' => 90000,
        'is_paid' => false,
        'academic_year_id' => $year->id,
    ]);
    Receipt::create([
        'fee_record_id' => $record->id,
        'amount_paid' => 30000,
        'balance' => 90000,
        'academic_year_id' => $year->id,
        'issued_at' => now(),
    ]);

    Exam::create([
        'academic_year_id' => $year->id,
        'term_id' => $term->id,
        'name' => 'Mid-term Exam',
        'starts_at' => now()->addDays(5),
        'is_published' => true,
    ]);

    $this->get(route('dashboard'))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Dashboard')
            ->where('stats', fn (array $stats) => collect($stats)->contains(fn ($item) => $item['label'] === 'Active Students' && $item['value'] === 1)
                && collect($stats)->contains(fn ($item) => $item['label'] === 'Outstanding Fees' && $item['value'] === 90000)
                && collect($stats)->contains(fn ($item) => $item['label'] === 'Published Exams' && $item['value'] === 1)
                && collect($stats)->contains(fn ($item) => $item['label'] === 'Active Staff' && $item['value'] === 1))
            ->has('payments', 1)
            ->has('events', 1)
        );
});
