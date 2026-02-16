<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Exam;
use App\Models\Mark;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\StudentEnrollment;
use App\Models\Subject;
use App\Models\SubjectAssignment;
use App\Services\GradeComputationService;
use App\Services\ResultComputationService;
use Illuminate\Database\Seeder;

class MarksSeeder extends Seeder
{
    public function run(): void
    {
        $academicYear = AcademicYear::query()->where('is_current', true)->first() ?? AcademicYear::query()->latest('id')->first();
        $exam = $academicYear
            ? Exam::query()->where('academic_year_id', $academicYear->id)->orderBy('id')->first()
            : null;

        if (!$academicYear || !$exam) {
            return;
        }

        $subjects = Subject::query()->orderBy('name')->take(5)->get();
        if ($subjects->isEmpty()) {
            return;
        }

        $gradeService = new GradeComputationService();
        $resultService = new ResultComputationService();

        foreach (['Primary 1', 'Primary 2', 'Primary 3', 'JSS 1', 'JSS 2', 'SS 1'] as $className) {
            $class = SchoolClass::query()->where('name', $className)->first();
            if (!$class) {
                continue;
            }

            $sectionId = Section::query()->forClass($class->id)->value('id');
            $enrollments = StudentEnrollment::query()
                ->where('class_id', $class->id)
                ->where('academic_year_id', $academicYear->id)
                ->where('is_current', true)
                ->get();

            if ($enrollments->isEmpty()) {
                continue;
            }

            foreach ($subjects as $subject) {
                SubjectAssignment::updateOrCreate(
                    [
                        'subject_id' => $subject->id,
                        'class_id' => $class->id,
                        'section_id' => $sectionId,
                        'academic_year_id' => $academicYear->id,
                    ],
                    [
                        'teacher_id' => null,
                        'is_active' => true,
                    ]
                );

                foreach ($enrollments as $enrollment) {
                    $scoreSeed = ($enrollment->student_id * 7) + ($subject->id * 3);
                    $t1 = 8 + ($scoreSeed % 8);
                    $t2 = 9 + ($scoreSeed % 8);
                    $exm = 35 + ($scoreSeed % 26);

                    Mark::updateOrCreate(
                        [
                            'student_id' => $enrollment->student_id,
                            'subject_id' => $subject->id,
                            'exam_id' => $exam->id,
                            'academic_year_id' => $academicYear->id,
                        ],
                        [
                            'class_id' => $class->id,
                            'section_id' => $sectionId,
                            't1' => $t1,
                            't2' => $t2,
                            't3' => null,
                            't4' => null,
                            'exm' => $exm,
                        ]
                    );
                }

                $gradeService->computeForExamSubject(
                    $exam->id,
                    $subject->id,
                    $class->id,
                    $sectionId,
                    $academicYear->id,
                );
            }

            $resultService->computeForExamClass(
                $exam->id,
                $class->id,
                $sectionId,
                $academicYear->id,
            );
        }
    }
}
