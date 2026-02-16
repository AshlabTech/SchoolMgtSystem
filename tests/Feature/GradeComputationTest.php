<?php

use App\Models\AcademicYear;
use App\Models\ClassLevel;
use App\Models\Exam;
use App\Models\Grade;
use App\Models\Mark;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Setting;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Term;
use App\Models\User;
use App\Services\GradeComputationService;

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

    $this->subject = Subject::create([
        'name' => 'Mathematics',
        'code' => 'MATH',
    ]);

    $this->exam = Exam::create([
        'academic_year_id' => $this->academicYear->id,
        'term_id' => $this->term->id,
        'name' => 'First Term Exam',
    ]);

    Grade::create(['class_level_id' => $this->classLevel->id, 'name' => 'A', 'mark_from' => 70, 'mark_to' => 100, 'remark' => 'Excellent']);
    Grade::create(['class_level_id' => $this->classLevel->id, 'name' => 'B', 'mark_from' => 60, 'mark_to' => 69, 'remark' => 'Very Good']);
    Grade::create(['class_level_id' => $this->classLevel->id, 'name' => 'C', 'mark_from' => 50, 'mark_to' => 59, 'remark' => 'Good']);
    Grade::create(['class_level_id' => $this->classLevel->id, 'name' => 'D', 'mark_from' => 40, 'mark_to' => 49, 'remark' => 'Fair']);
    Grade::create(['class_level_id' => $this->classLevel->id, 'name' => 'F', 'mark_from' => 0, 'mark_to' => 39, 'remark' => 'Fail']);

    Setting::create(['key' => 'number_of_ca_components', 'group' => 'grading', 'type' => 'select', 'value' => '2']);
    Setting::create(['key' => 'auto_compute_grade', 'group' => 'grading', 'type' => 'boolean', 'value' => '1']);
    Setting::create(['key' => 'auto_compute_subject_position', 'group' => 'grading', 'type' => 'boolean', 'value' => '1']);
    Setting::create(['key' => 'ca_total_weight', 'group' => 'grading', 'type' => 'number', 'value' => '40']);
    Setting::create(['key' => 'exam_weight', 'group' => 'grading', 'type' => 'number', 'value' => '60']);
});

function createStudentWithUser(): Student
{
    $user = User::factory()->create();

    return Student::create([
        'user_id' => $user->id,
        'admission_no' => 'ADM-' . $user->id,
        'joined_at' => now(),
        'status' => 'active',
    ]);
}

it('computes TCA from CA component scores', function () {
    $student = createStudentWithUser();

    Mark::create([
        'student_id' => $student->id,
        'subject_id' => $this->subject->id,
        'class_id' => $this->schoolClass->id,
        'section_id' => $this->section->id,
        'exam_id' => $this->exam->id,
        'academic_year_id' => $this->academicYear->id,
        't1' => 15,
        't2' => 20,
        'exm' => 50,
    ]);

    $service = new GradeComputationService();
    $service->computeForExamSubject(
        $this->exam->id,
        $this->subject->id,
        $this->schoolClass->id,
        $this->section->id,
        $this->academicYear->id,
    );

    $mark = Mark::where('student_id', $student->id)->first();

    expect($mark->tca)->toBe(35)
        ->and($mark->cum)->toBe(85);
});

it('assigns correct grade based on total score', function () {
    $student = createStudentWithUser();

    Mark::create([
        'student_id' => $student->id,
        'subject_id' => $this->subject->id,
        'class_id' => $this->schoolClass->id,
        'section_id' => $this->section->id,
        'exam_id' => $this->exam->id,
        'academic_year_id' => $this->academicYear->id,
        't1' => 10,
        't2' => 10,
        'exm' => 35,
    ]);

    $service = new GradeComputationService();
    $service->computeForExamSubject(
        $this->exam->id,
        $this->subject->id,
        $this->schoolClass->id,
        $this->section->id,
        $this->academicYear->id,
    );

    $mark = Mark::where('student_id', $student->id)->first();

    expect($mark->cum)->toBe(55)
        ->and($mark->grade)->not->toBeNull()
        ->and($mark->grade->name)->toBe('C')
        ->and($mark->grade->remark)->toBe('Good');
});

it('computes subject positions with correct ranking', function () {
    $student1 = createStudentWithUser();
    $student2 = createStudentWithUser();
    $student3 = createStudentWithUser();

    Mark::create([
        'student_id' => $student1->id,
        'subject_id' => $this->subject->id,
        'class_id' => $this->schoolClass->id,
        'section_id' => $this->section->id,
        'exam_id' => $this->exam->id,
        'academic_year_id' => $this->academicYear->id,
        't1' => 20,
        't2' => 20,
        'exm' => 50,
    ]);

    Mark::create([
        'student_id' => $student2->id,
        'subject_id' => $this->subject->id,
        'class_id' => $this->schoolClass->id,
        'section_id' => $this->section->id,
        'exam_id' => $this->exam->id,
        'academic_year_id' => $this->academicYear->id,
        't1' => 10,
        't2' => 10,
        'exm' => 30,
    ]);

    Mark::create([
        'student_id' => $student3->id,
        'subject_id' => $this->subject->id,
        'class_id' => $this->schoolClass->id,
        'section_id' => $this->section->id,
        'exam_id' => $this->exam->id,
        'academic_year_id' => $this->academicYear->id,
        't1' => 15,
        't2' => 15,
        'exm' => 40,
    ]);

    $service = new GradeComputationService();
    $service->computeForExamSubject(
        $this->exam->id,
        $this->subject->id,
        $this->schoolClass->id,
        $this->section->id,
        $this->academicYear->id,
    );

    $mark1 = Mark::where('student_id', $student1->id)->first();
    $mark2 = Mark::where('student_id', $student2->id)->first();
    $mark3 = Mark::where('student_id', $student3->id)->first();

    expect($mark1->sub_pos)->toBe(1)
        ->and($mark3->sub_pos)->toBe(2)
        ->and($mark2->sub_pos)->toBe(3);
});

it('handles tied scores with equal positions', function () {
    $student1 = createStudentWithUser();
    $student2 = createStudentWithUser();

    Mark::create([
        'student_id' => $student1->id,
        'subject_id' => $this->subject->id,
        'class_id' => $this->schoolClass->id,
        'section_id' => $this->section->id,
        'exam_id' => $this->exam->id,
        'academic_year_id' => $this->academicYear->id,
        't1' => 15,
        't2' => 15,
        'exm' => 40,
    ]);

    Mark::create([
        'student_id' => $student2->id,
        'subject_id' => $this->subject->id,
        'class_id' => $this->schoolClass->id,
        'section_id' => $this->section->id,
        'exam_id' => $this->exam->id,
        'academic_year_id' => $this->academicYear->id,
        't1' => 15,
        't2' => 15,
        'exm' => 40,
    ]);

    $service = new GradeComputationService();
    $service->computeForExamSubject(
        $this->exam->id,
        $this->subject->id,
        $this->schoolClass->id,
        $this->section->id,
        $this->academicYear->id,
    );

    $mark1 = Mark::where('student_id', $student1->id)->first();
    $mark2 = Mark::where('student_id', $student2->id)->first();

    expect($mark1->sub_pos)->toBe(1)
        ->and($mark2->sub_pos)->toBe(1);
});

it('respects number_of_ca_components setting', function () {
    Setting::where('key', 'number_of_ca_components')->update(['value' => '4']);

    $student = createStudentWithUser();

    Mark::create([
        'student_id' => $student->id,
        'subject_id' => $this->subject->id,
        'class_id' => $this->schoolClass->id,
        'section_id' => $this->section->id,
        'exam_id' => $this->exam->id,
        'academic_year_id' => $this->academicYear->id,
        't1' => 5,
        't2' => 5,
        't3' => 5,
        't4' => 5,
        'exm' => 40,
    ]);

    $service = new GradeComputationService();
    $service->computeForExamSubject(
        $this->exam->id,
        $this->subject->id,
        $this->schoolClass->id,
        $this->section->id,
        $this->academicYear->id,
    );

    $mark = Mark::where('student_id', $student->id)->first();

    expect($mark->tca)->toBe(20)
        ->and($mark->cum)->toBe(60);
});

it('computes cumulative average from current and previous term scores', function () {
    $student = createStudentWithUser();

    Mark::create([
        'student_id' => $student->id,
        'subject_id' => $this->subject->id,
        'class_id' => $this->schoolClass->id,
        'section_id' => $this->section->id,
        'exam_id' => $this->exam->id,
        'academic_year_id' => $this->academicYear->id,
        't1' => 15,
        't2' => 20,
        'exm' => 50,
        'tex1' => 70, // First term score
        'tex2' => 80, // Second term score
    ]);

    $service = new GradeComputationService();
    $service->computeForExamSubject(
        $this->exam->id,
        $this->subject->id,
        $this->schoolClass->id,
        $this->section->id,
        $this->academicYear->id,
    );

    $mark = Mark::where('student_id', $student->id)->first();

    // Current term: 85, tex1: 70, tex2: 80
    // Average: (85 + 70 + 80) / 3 = 78.33
    expect($mark->cum)->toBe(85)
        ->and($mark->cum_ave)->toBe('78.33');
});

it('computes cumulative average with only current term when no previous terms exist', function () {
    $student = createStudentWithUser();

    Mark::create([
        'student_id' => $student->id,
        'subject_id' => $this->subject->id,
        'class_id' => $this->schoolClass->id,
        'section_id' => $this->section->id,
        'exam_id' => $this->exam->id,
        'academic_year_id' => $this->academicYear->id,
        't1' => 15,
        't2' => 20,
        'exm' => 50,
    ]);

    $service = new GradeComputationService();
    $service->computeForExamSubject(
        $this->exam->id,
        $this->subject->id,
        $this->schoolClass->id,
        $this->section->id,
        $this->academicYear->id,
    );

    $mark = Mark::where('student_id', $student->id)->first();

    // Only current term: 85
    // Average: 85 / 1 = 85.00
    expect($mark->cum)->toBe(85)
        ->and($mark->cum_ave)->toBe('85.00');
});
