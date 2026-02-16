<?php

use App\Models\AcademicYear;
use App\Models\ClassLevel;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Skill;
use App\Models\SkillScore;
use App\Models\Student;
use App\Models\Term;
use App\Models\User;
use App\Services\DomainComputationService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

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

    $this->exam = Exam::create([
        'name' => 'First Term Exam',
        'academic_year_id' => $this->academicYear->id,
        'term_id' => $this->term->id,
        'starts_at' => now()->addDays(5),
        'ends_at' => now()->addDays(10),
        'is_published' => true,
    ]);

    $user = User::factory()->create();
    $this->student = Student::create([
        'user_id' => $user->id,
        'admission_no' => 'ADM001',
        'admission_date' => '2025-09-01',
        'status' => 'active',
        'is_graduated' => false,
    ]);

    // Create skills
    $this->psychomotorSkill1 = Skill::create([
        'name' => 'Handwriting',
        'skill_type' => Skill::SKILL_TYPE_PSYCHOMOTOR,
        'class_level_id' => $this->classLevel->id,
    ]);

    $this->psychomotorSkill2 = Skill::create([
        'name' => 'Drawing',
        'skill_type' => Skill::SKILL_TYPE_PSYCHOMOTOR,
        'class_level_id' => $this->classLevel->id,
    ]);

    $this->affectiveSkill1 = Skill::create([
        'name' => 'Punctuality',
        'skill_type' => Skill::SKILL_TYPE_AFFECTIVE,
        'class_level_id' => $this->classLevel->id,
    ]);

    $this->affectiveSkill2 = Skill::create([
        'name' => 'Neatness',
        'skill_type' => Skill::SKILL_TYPE_AFFECTIVE,
        'class_level_id' => $this->classLevel->id,
    ]);
});

test('it can create skill scores', function () {
    $skillScore = SkillScore::create([
        'student_id' => $this->student->id,
        'skill_id' => $this->psychomotorSkill1->id,
        'exam_id' => $this->exam->id,
        'class_id' => $this->schoolClass->id,
        'section_id' => $this->section->id,
        'academic_year_id' => $this->academicYear->id,
        'rating' => 4,
        'comment' => 'Good handwriting',
    ]);

    expect($skillScore)->toBeInstanceOf(SkillScore::class);
    expect($skillScore->rating)->toBe(4);
    expect($skillScore->student_id)->toBe($this->student->id);
    expect($skillScore->skill_id)->toBe($this->psychomotorSkill1->id);
});

test('domain computation service calculates average psychomotor score', function () {
    // Create skill scores
    SkillScore::create([
        'student_id' => $this->student->id,
        'skill_id' => $this->psychomotorSkill1->id,
        'exam_id' => $this->exam->id,
        'class_id' => $this->schoolClass->id,
        'section_id' => $this->section->id,
        'academic_year_id' => $this->academicYear->id,
        'rating' => 4,
    ]);

    SkillScore::create([
        'student_id' => $this->student->id,
        'skill_id' => $this->psychomotorSkill2->id,
        'exam_id' => $this->exam->id,
        'class_id' => $this->schoolClass->id,
        'section_id' => $this->section->id,
        'academic_year_id' => $this->academicYear->id,
        'rating' => 5,
    ]);

    // Run computation
    $service = new DomainComputationService();
    $service->computeForExamClass(
        $this->exam->id,
        $this->schoolClass->id,
        $this->section->id,
        $this->academicYear->id
    );

    // Check result
    $result = ExamResult::where('student_id', $this->student->id)
        ->where('exam_id', $this->exam->id)
        ->first();

    expect($result)->not->toBeNull();
    expect($result->psychomotor)->toBe('4.5/5'); // (4 + 5) / 2 = 4.5
});

test('domain computation service calculates average affective score', function () {
    // Create skill scores
    SkillScore::create([
        'student_id' => $this->student->id,
        'skill_id' => $this->affectiveSkill1->id,
        'exam_id' => $this->exam->id,
        'class_id' => $this->schoolClass->id,
        'section_id' => $this->section->id,
        'academic_year_id' => $this->academicYear->id,
        'rating' => 3,
    ]);

    SkillScore::create([
        'student_id' => $this->student->id,
        'skill_id' => $this->affectiveSkill2->id,
        'exam_id' => $this->exam->id,
        'class_id' => $this->schoolClass->id,
        'section_id' => $this->section->id,
        'academic_year_id' => $this->academicYear->id,
        'rating' => 5,
    ]);

    // Run computation
    $service = new DomainComputationService();
    $service->computeForExamClass(
        $this->exam->id,
        $this->schoolClass->id,
        $this->section->id,
        $this->academicYear->id
    );

    // Check result
    $result = ExamResult::where('student_id', $this->student->id)
        ->where('exam_id', $this->exam->id)
        ->first();

    expect($result)->not->toBeNull();
    expect($result->affective)->toBe('4.0/5'); // (3 + 5) / 2 = 4.0
});

test('domain computation handles students with no skill scores', function () {
    $service = new DomainComputationService();
    $service->computeForExamClass(
        $this->exam->id,
        $this->schoolClass->id,
        $this->section->id,
        $this->academicYear->id
    );

    $result = ExamResult::where('student_id', $this->student->id)
        ->where('exam_id', $this->exam->id)
        ->first();

    // Should not create a result if no scores exist
    expect($result)->toBeNull();
});

test('domain computation correctly separates psychomotor and affective scores', function () {
    // Create mixed skill scores
    SkillScore::create([
        'student_id' => $this->student->id,
        'skill_id' => $this->psychomotorSkill1->id,
        'exam_id' => $this->exam->id,
        'class_id' => $this->schoolClass->id,
        'section_id' => $this->section->id,
        'academic_year_id' => $this->academicYear->id,
        'rating' => 5,
    ]);

    SkillScore::create([
        'student_id' => $this->student->id,
        'skill_id' => $this->affectiveSkill1->id,
        'exam_id' => $this->exam->id,
        'class_id' => $this->schoolClass->id,
        'section_id' => $this->section->id,
        'academic_year_id' => $this->academicYear->id,
        'rating' => 3,
    ]);

    // Run computation
    $service = new DomainComputationService();
    $service->computeForExamClass(
        $this->exam->id,
        $this->schoolClass->id,
        $this->section->id,
        $this->academicYear->id
    );

    $result = ExamResult::where('student_id', $this->student->id)
        ->where('exam_id', $this->exam->id)
        ->first();

    expect($result)->not->toBeNull();
    expect($result->psychomotor)->toBe('5.0/5'); // Only psychomotor skill
    expect($result->affective)->toBe('3.0/5'); // Only affective skill
});

test('rating label helper returns correct labels', function () {
    $service = new DomainComputationService();

    expect($service->getRatingLabel(5))->toBe('Excellent');
    expect($service->getRatingLabel(4))->toBe('Good');
    expect($service->getRatingLabel(3))->toBe('Satisfactory');
    expect($service->getRatingLabel(2))->toBe('Needs Improvement');
    expect($service->getRatingLabel(1))->toBe('Poor');
    expect($service->getRatingLabel(0))->toBe('Not Rated');
});
