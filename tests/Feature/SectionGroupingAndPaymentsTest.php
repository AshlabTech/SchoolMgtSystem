<?php

use App\Models\AcademicYear;
use App\Models\ClassLevel;
use App\Models\FeeRecord;
use App\Models\InvoiceType;
use App\Models\PaymentCategory;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Models\Term;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Middleware\PermissionMiddleware;

it('stores a grouped section across multiple classes', function () {
    $this->withoutMiddleware(PermissionMiddleware::class);

    $user = User::factory()->create();
    $this->actingAs($user);

    $level = ClassLevel::create(['name' => 'Primary Group', 'code' => 'PG']);
    $class1 = SchoolClass::create(['class_level_id' => $level->id, 'name' => 'Primary 1']);
    $class2 = SchoolClass::create(['class_level_id' => $level->id, 'name' => 'Primary 2']);

    $this->post(route('academics.sections.store'), [
        'name' => 'Primary',
        'class_ids' => [$class1->id, $class2->id],
    ])->assertRedirect();

    $section = Section::query()->firstOrFail();

    expect($section->class_id)->toBe($class1->id)
        ->and($section->name)->toBe('Primary');

    if (Schema::hasTable('class_section')) {
        $assigned = $section->schoolClasses()->pluck('id')->sort()->values()->all();
        expect($assigned)->toBe(collect([$class1->id, $class2->id])->sort()->values()->all());
    }
});

it('auto-selects section for payment definition when class is provided', function () {
    $this->withoutMiddleware(PermissionMiddleware::class);

    $user = User::factory()->create();
    $this->actingAs($user);

    $level = ClassLevel::create(['name' => 'Secondary Group', 'code' => 'SG']);
    $class = SchoolClass::create(['class_level_id' => $level->id, 'name' => 'SS 1']);
    $section = Section::create(['class_id' => $class->id, 'name' => 'Secondary']);
    $category = PaymentCategory::create(['name' => 'Development Fee']);

    $this->post(route('payments.definitions.store'), [
        'name' => 'Term Levy',
        'amount' => 25000,
        'payment_category_id' => $category->id,
        'class_id' => $class->id,
    ])->assertRedirect();

    $definition = InvoiceType::query()->latest('id')->first();

    expect($definition)->not->toBeNull()
        ->and($definition->section_id)->toBe($section->id);
});

it('prevents paying above outstanding balance', function () {
    $this->withoutMiddleware(PermissionMiddleware::class);

    $user = User::factory()->create();
    $this->actingAs($user);

    $student = Student::create([
        'user_id' => User::factory()->create()->id,
        'admission_no' => 'ADM-OVERPAY',
        'joined_at' => now(),
        'status' => 'active',
    ]);
    $category = PaymentCategory::create(['name' => 'Tuition Cap']);
    $invoiceType = InvoiceType::create([
        'name' => 'Capped Tuition',
        'amount' => 10000,
        'payment_category_id' => $category->id,
    ]);
    $record = FeeRecord::create([
        'invoice_type_id' => $invoiceType->id,
        'student_id' => $student->id,
        'reference' => 'CAP-100',
        'amount_paid' => 0,
        'balance' => 10000,
        'is_paid' => false,
    ]);

    $this->from('/payments')->post(route('payments.records.pay', $record), [
        'amount' => 12000,
    ])->assertRedirect('/payments')
        ->assertSessionHasErrors('amount');
});

it('filters payment records by class and status', function () {
    $this->withoutMiddleware(PermissionMiddleware::class);

    $user = User::factory()->create();
    $this->actingAs($user);

    $year = AcademicYear::create(['name' => '2025/2026']);
    $term = Term::create(['academic_year_id' => $year->id, 'name' => 'First Term', 'order' => 1]);
    $level = ClassLevel::create(['name' => 'Primary', 'code' => 'PRI']);
    $classA = SchoolClass::create(['class_level_id' => $level->id, 'name' => 'Primary 1']);
    $classB = SchoolClass::create(['class_level_id' => $level->id, 'name' => 'Primary 2']);
    $sectionA = Section::create(['class_id' => $classA->id, 'name' => 'A']);
    $sectionB = Section::create(['class_id' => $classB->id, 'name' => 'B']);
    $category = PaymentCategory::create(['name' => 'Tuition']);
    $invoiceType = InvoiceType::create([
        'name' => 'Term Fee',
        'amount' => 20000,
        'payment_category_id' => $category->id,
        'term_id' => $term->id,
        'academic_year_id' => $year->id,
    ]);

    $studentOne = Student::create([
        'user_id' => User::factory()->create()->id,
        'admission_no' => 'ADM-FILTER-1',
        'joined_at' => now(),
        'status' => 'active',
    ]);
    $studentTwo = Student::create([
        'user_id' => User::factory()->create()->id,
        'admission_no' => 'ADM-FILTER-2',
        'joined_at' => now(),
        'status' => 'active',
    ]);

    StudentEnrollment::create([
        'student_id' => $studentOne->id,
        'class_id' => $classA->id,
        'section_id' => $sectionA->id,
        'academic_year_id' => $year->id,
        'is_current' => true,
    ]);
    StudentEnrollment::create([
        'student_id' => $studentTwo->id,
        'class_id' => $classB->id,
        'section_id' => $sectionB->id,
        'academic_year_id' => $year->id,
        'is_current' => true,
    ]);

    FeeRecord::create([
        'invoice_type_id' => $invoiceType->id,
        'student_id' => $studentOne->id,
        'reference' => 'REC-FILTER-1',
        'amount_paid' => 0,
        'balance' => 20000,
        'is_paid' => false,
        'academic_year_id' => $year->id,
    ]);
    FeeRecord::create([
        'invoice_type_id' => $invoiceType->id,
        'student_id' => $studentTwo->id,
        'reference' => 'REC-FILTER-2',
        'amount_paid' => 20000,
        'balance' => 0,
        'is_paid' => true,
        'academic_year_id' => $year->id,
    ]);

    $this->get(route('payments.index', ['class_id' => $classA->id, 'status' => 'unpaid']))
        ->assertSuccessful()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Payments/Index')
            ->has('records', 1)
            ->where('records.0.reference', 'REC-FILTER-1')
        );
});

it('exports payment report with status filtering', function () {
    $this->withoutMiddleware(PermissionMiddleware::class);

    $user = User::factory()->create();
    $this->actingAs($user);

    $category = PaymentCategory::create(['name' => 'Development']);
    $invoiceType = InvoiceType::create([
        'name' => 'Development Fee',
        'amount' => 15000,
        'payment_category_id' => $category->id,
    ]);
    $student = Student::create([
        'user_id' => User::factory()->create()->id,
        'admission_no' => 'ADM-EXPORT-1',
        'joined_at' => now(),
        'status' => 'active',
    ]);

    FeeRecord::create([
        'invoice_type_id' => $invoiceType->id,
        'student_id' => $student->id,
        'reference' => 'REC-EXPORT-1',
        'amount_paid' => 0,
        'balance' => 15000,
        'is_paid' => false,
    ]);

    $response = $this->get(route('payments.export', ['scope' => 'unpaid']));
    $content = $response->streamedContent();

    $response->assertSuccessful();
    expect($content)->toContain('Status')
        ->toContain('Unpaid')
        ->toContain('REC-EXPORT-1');
});
