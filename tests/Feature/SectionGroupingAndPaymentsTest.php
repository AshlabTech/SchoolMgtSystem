<?php

use App\Models\ClassLevel;
use App\Models\FeeRecord;
use App\Models\InvoiceType;
use App\Models\PaymentCategory;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\User;
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
