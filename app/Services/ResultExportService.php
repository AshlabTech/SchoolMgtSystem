<?php

namespace App\Services;

use App\Models\ExamResult;
use App\Models\Mark;
use App\Models\SkillScore;
use App\Models\Student;
use Illuminate\Support\Collection;

class ResultExportService
{
    /**
     * Export individual student result data.
     */
    public function exportIndividualResult(int $studentId, int $examId, ?int $academicYearId): array
    {
        $student = Student::with(['user.profile', 'currentEnrollment.schoolClass', 'currentEnrollment.section'])
            ->findOrFail($studentId);

        $marks = Mark::with(['subject', 'grade'])
            ->where('student_id', $studentId)
            ->where('exam_id', $examId)
            ->when($academicYearId, fn ($q) => $q->where('academic_year_id', $academicYearId))
            ->get();

        $examResult = ExamResult::query()
            ->where('student_id', $studentId)
            ->where('exam_id', $examId)
            ->when($academicYearId, fn ($q) => $q->where('academic_year_id', $academicYearId))
            ->first();

        $skillScores = SkillScore::query()
            ->with('skill')
            ->where('student_id', $studentId)
            ->where('exam_id', $examId)
            ->when($academicYearId, fn ($q) => $q->where('academic_year_id', $academicYearId))
            ->get();

        return [
            'student' => [
                'id' => $student->id,
                'name' => $student->user->name ?? 'Unknown',
                'admission_no' => $student->admission_no,
                'class' => $student->currentEnrollment?->schoolClass?->name,
                'section' => $student->currentEnrollment?->section?->name,
            ],
            'marks' => $marks->map(fn ($mark) => [
                'subject' => $mark->subject?->name,
                't1' => $mark->t1,
                't2' => $mark->t2,
                't3' => $mark->t3,
                't4' => $mark->t4,
                'tca' => $mark->tca,
                'exam' => $mark->exm,
                'total' => $mark->cum,
                'cum_ave' => $mark->cum_ave,
                'grade' => $mark->grade?->name,
                'remark' => $mark->grade?->remark,
                'position' => $mark->sub_pos,
            ]),
            'result' => $examResult ? [
                'total' => $examResult->total,
                'average' => $examResult->average,
                'class_average' => $examResult->class_average,
                'position' => $examResult->position,
                'psychomotor' => $examResult->psychomotor,
                'affective' => $examResult->affective,
                'teacher_comment' => $examResult->teacher_comment,
            ] : null,
            'skills' => $skillScores->map(fn ($score) => [
                'skill' => $score->skill?->name,
                'type' => $score->skill?->skill_type,
                'rating' => $score->rating,
                'comment' => $score->comment,
            ]),
        ];
    }

    /**
     * Export class results for all students.
     */
    public function exportClassResults(int $classId, int $examId, ?int $sectionId, ?int $academicYearId): Collection
    {
        $students = Student::with(['user.profile'])
            ->whereHas('currentEnrollment', function ($q) use ($classId, $sectionId) {
                $q->where('class_id', $classId);
                if ($sectionId) {
                    $q->where('section_id', $sectionId);
                }
            })
            ->get();

        return $students->map(function ($student) use ($examId, $academicYearId) {
            $marks = Mark::with(['subject', 'grade'])
                ->where('student_id', $student->id)
                ->where('exam_id', $examId)
                ->when($academicYearId, fn ($q) => $q->where('academic_year_id', $academicYearId))
                ->get();

            $examResult = ExamResult::query()
                ->where('student_id', $student->id)
                ->where('exam_id', $examId)
                ->when($academicYearId, fn ($q) => $q->where('academic_year_id', $academicYearId))
                ->first();

            return [
                'student' => [
                    'id' => $student->id,
                    'name' => $student->user->name ?? 'Unknown',
                    'admission_no' => $student->admission_no,
                ],
                'marks' => $marks->map(fn ($mark) => [
                    'subject' => $mark->subject?->name,
                    'total' => $mark->cum,
                    'grade' => $mark->grade?->name,
                ]),
                'result' => $examResult ? [
                    'total' => $examResult->total,
                    'average' => $examResult->average,
                    'position' => $examResult->position,
                ] : null,
            ];
        });
    }
}
