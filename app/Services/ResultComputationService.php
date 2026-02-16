<?php

namespace App\Services;

use App\Models\ExamResult;
use App\Models\Mark;
use App\Models\ResultComment;
use App\Models\Setting;

class ResultComputationService
{
    /**
     * Compute overall results (totals, averages, positions) for a class in an exam.
     */
    public function computeForExamClass(int $examId, int $classId, ?int $sectionId, ?int $academicYearId): void
    {
        $marks = Mark::query()
            ->where('exam_id', $examId)
            ->where('class_id', $classId)
            ->when($sectionId, fn ($q) => $q->where('section_id', $sectionId))
            ->when($academicYearId, fn ($q) => $q->where('academic_year_id', $academicYearId))
            ->get();

        if ($marks->isEmpty()) {
            return;
        }

        $studentMarks = $marks->groupBy('student_id');
        $showPosition = (bool) Setting::where('key', 'show_position_on_result')->value('value');
        $autoApplyComment = (bool) Setting::where('key', 'auto_apply_result_comment')->value('value');
        $defaultTeacherComment = $autoApplyComment
            ? ResultComment::query()
                ->where('type', 'teacher')
                ->where('is_active', true)
                ->orderByDesc('is_default')
                ->orderBy('sort_order')
                ->orderBy('id')
                ->value('comment')
            : null;

        $aggregates = [];

        foreach ($studentMarks as $studentId => $subjectMarks) {
            $total = $subjectMarks->sum('cum');
            $subjectCount = $subjectMarks->count();
            $average = $subjectCount > 0 ? round($total / $subjectCount, 2) : 0;

            $aggregates[] = [
                'student_id' => $studentId,
                'total' => $total,
                'average' => $average,
                'subject_count' => $subjectCount,
                'section_id' => $subjectMarks->first()->section_id,
            ];
        }

        usort($aggregates, fn ($a, $b) => $b['total'] <=> $a['total']);

        $classTotal = array_sum(array_column($aggregates, 'average'));
        $studentCount = count($aggregates);
        $classAverage = $studentCount > 0 ? round($classTotal / $studentCount, 2) : 0;

        $position = 0;
        $lastTotal = null;
        $skipCount = 0;

        foreach ($aggregates as &$agg) {
            if ($agg['total'] !== $lastTotal) {
                $position += 1 + $skipCount;
                $skipCount = 0;
            } else {
                $skipCount++;
            }

            $agg['position'] = $showPosition ? $position : null;
            $lastTotal = $agg['total'];
        }
        unset($agg);

        foreach ($aggregates as $agg) {
            $payload = [
                'class_id' => $classId,
                'section_id' => $agg['section_id'],
                'total' => $agg['total'],
                'average' => (string) $agg['average'],
                'class_average' => (string) $classAverage,
                'position' => $agg['position'],
            ];

            if ($defaultTeacherComment) {
                $payload['teacher_comment'] = $defaultTeacherComment;
            }

            ExamResult::updateOrCreate(
                [
                    'exam_id' => $examId,
                    'student_id' => $agg['student_id'],
                    'academic_year_id' => $academicYearId,
                ],
                $payload
            );
        }
    }
}
