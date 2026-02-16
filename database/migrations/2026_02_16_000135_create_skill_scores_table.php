<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skill_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('skill_id')->constrained('skills')->cascadeOnDelete();
            $table->foreignId('exam_id')->constrained('exams')->cascadeOnDelete();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('section_id')->nullable()->constrained('sections')->nullOnDelete();
            $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
            $table->integer('rating')->nullable()->comment('Rating scale: 1-5 where 5 is excellent');
            $table->text('comment')->nullable();
            $table->timestamps();
            $table->unique(['student_id', 'skill_id', 'exam_id', 'academic_year_id'], 'skill_scores_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skill_scores');
    }
};
