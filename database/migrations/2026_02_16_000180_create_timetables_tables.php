<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('timetables', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('section_id')->nullable()->constrained('sections')->nullOnDelete();
            $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
            $table->foreignId('term_id')->nullable()->constrained('terms')->nullOnDelete();
            $table->foreignId('exam_id')->nullable()->constrained('exams')->nullOnDelete();
            $table->string('type')->default('class');
            $table->timestamps();

            $table->unique([
                'class_id',
                'section_id',
                'academic_year_id',
                'term_id',
                'exam_id',
                'type',
            ], 'timetables_unique');
        });

        Schema::create('timeslots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('timetable_id')->constrained('timetables')->cascadeOnDelete();
            $table->string('label');
            $table->time('time_from');
            $table->time('time_to');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['timetable_id', 'time_from', 'time_to']);
        });

        Schema::create('timetable_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('timetable_id')->constrained('timetables')->cascadeOnDelete();
            $table->foreignId('timeslot_id')->constrained('timeslots')->cascadeOnDelete();
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedTinyInteger('day_of_week')->nullable();
            $table->date('exam_date')->nullable();
            $table->string('room')->nullable();
            $table->timestamps();

            $table->unique(['timetable_id', 'timeslot_id', 'day_of_week'], 'timetable_entries_day_unique');
            $table->unique(['timetable_id', 'timeslot_id', 'exam_date'], 'timetable_entries_exam_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('timetable_entries');
        Schema::dropIfExists('timeslots');
        Schema::dropIfExists('timetables');
    }
};
