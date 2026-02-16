<?php

use App\Models\AcademicYear;
use App\Models\ClassLevel;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Mark;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Setting;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Term;
use App\Models\User;
use App\Services\ResultComputationService;

beforeEach(function () {
    $this->academicYear = AcademicYear::create([
        'name' => '2025/2026',
        'starts_at' => '2025-09-01',
        'ends_at' => '2026-07-31',
        'is_current' => true,
    ]);

    $this->term = Term::create([
        'academic_year_id' => $this->academicYear->id,
        'name' => 'First Term',
        'order' => 1,
        'starts_at' => '2025-09-01',
        'ends_at' => '2025-12-15',
        'is_current' => true,
    ]);

    $this->classLevel = ClassLevel::create([
        'name' => 'JSS',
        'code' => 'JSS',
    ]);

    $this->schoolClass = SchoolClass::create([
        'class_level_id' => $this->classLevel->id,
        'name' => 'JSS 1',
        'code' => 'JSS1',
    ]);

    $this->section = Section::create([
        'class_id' => $this->schoolClass->id,
        'name' => 'A',
    ]);

    $this->subject1 = Subject::create(['name' => 'Mathematics', 'code' => 'MATH']);
    $this->subject2 = Subject::create(['name' => 'English', 'code' => 'ENG']);

    $this->exam = Exam::create([
        'academic_year_id' => $this->academicYear->id,
        'term_id' => $this->term->id,
        'name' => 'First Term Exam',
    ]);

    Setting::create(['key' => 'show_position_on_result', 'group' => 'results', 'type' => 'boolean', 'value' => '1']);
});

function createStudentForResult(): Student
{
    $user = User::factory()->create();

    return Student::create([
        'user_id' => $user->id,
        'admission_no' => 'ADM-' . $user->id,
        'joined_at' => now(),
        'status' => 'active',
    ]);
}

it('computes class results with positions and averages', function () {
    $student1 = createStudentForResult();
    $student2 = createStudentForResult();

    Mark::create([
        'student_id' => $student1->id,
        'subject_id' => $this->subject1->id,
        'class_id' => $this->schoolClass->id,
        'section_id' => $this->section->id,
        'exam_id' => $this->exam->id,
        'academic_year_id' => $this->academicYear->id,
        'cum' => 80,
    ]);
    Mark::create([
        'student_id' => $student1->id,
        'subject_id' => $this->subject2->id,
        'class_id' => $this->schoolClass->id,
        'section_id' => $this->section->id,
        'exam_id' => $this->exam->id,
        'academic_year_id' => $this->academicYear->id,
        'cum' => 70,
    ]);

    Mark::create([
        'student_id' => $student2->id,
        'subject_id' => $this->subject1->id,
        'class_id' => $this->schoolClass->id,
        'section_id' => $this->section->id,
        'exam_id' => $this->exam->id,
        'academic_year_id' => $this->academicYear->id,
        'cum' => 60,
    ]);
    Mark::create([
        'student_id' => $student2->id,
        'subject_id' => $this->subject2->id,
        'class_id' => $this->schoolClass->id,
        'section_id' => $this->section->id,
        'exam_id' => $this->exam->id,
        'academic_year_id' => $this->academicYear->id,
        'cum' => 50,
    ]);

    $service = new ResultComputationService();
    $service->computeForExamClass(
        $this->exam->id,
        $this->schoolClass->id,
        $this->section->id,
        $this->academicYear->id,
    );

    $result1 = ExamResult::where('student_id', $student1->id)->first();
    $result2 = ExamResult::where('student_id', $student2->id)->first();

    expect($result1)->not->toBeNull()
        ->and($result1->total)->toBe(150)
        ->and((float) $result1->average)->toBe(75.0)
        ->and($result1->position)->toBe(1)
        ->and($result2->total)->toBe(110)
        ->and((float) $result2->average)->toBe(55.0)
        ->and($result2->position)->toBe(2);
});

it('hides positions when setting is disabled', function () {
    Setting::where('key', 'show_position_on_result')->update(['value' => '0']);

    $student = createStudentForResult();

    Mark::create([
        'student_id' => $student->id,
        'subject_id' => $this->subject1->id,
        'class_id' => $this->schoolClass->id,
        'section_id' => $this->section->id,
        'exam_id' => $this->exam->id,
        'academic_year_id' => $this->academicYear->id,
        'cum' => 80,
    ]);

    $service = new ResultComputationService();
    $service->computeForExamClass(
        $this->exam->id,
        $this->schoolClass->id,
        $this->section->id,
        $this->academicYear->id,
    );

    $result = ExamResult::where('student_id', $student->id)->first();

    expect($result)->not->toBeNull()
        ->and($result->position)->toBeNull();
});

it('computes class average across all students', function () {
    $student1 = createStudentForResult();
    $student2 = createStudentForResult();

    Mark::create([
        'student_id' => $student1->id,
        'subject_id' => $this->subject1->id,
        'class_id' => $this->schoolClass->id,
        'section_id' => $this->section->id,
        'exam_id' => $this->exam->id,
        'academic_year_id' => $this->academicYear->id,
        'cum' => 80,
    ]);

    Mark::create([
        'student_id' => $student2->id,
        'subject_id' => $this->subject1->id,
        'class_id' => $this->schoolClass->id,
        'section_id' => $this->section->id,
        'exam_id' => $this->exam->id,
        'academic_year_id' => $this->academicYear->id,
        'cum' => 60,
    ]);

    $service = new ResultComputationService();
    $service->computeForExamClass(
        $this->exam->id,
        $this->schoolClass->id,
        $this->section->id,
        $this->academicYear->id,
    );

    $result1 = ExamResult::where('student_id', $student1->id)->first();
    $result2 = ExamResult::where('student_id', $student2->id)->first();

    expect((float) $result1->class_average)->toBe(70.0)
        ->and((float) $result2->class_average)->toBe(70.0);
});
