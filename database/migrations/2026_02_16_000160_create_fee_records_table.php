<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fee_definition_id')->constrained('fee_definitions')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->string('reference', 100)->unique()->nullable();
            $table->integer('amount_paid')->default(0);
            $table->integer('balance')->default(0);
            $table->boolean('is_paid')->default(false);
            $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
            $table->timestamps();
            $table->unique(['fee_definition_id', 'student_id', 'academic_year_id'], 'fee_records_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_records');
    }
};
