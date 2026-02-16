<?php

namespace App\Services;

use App\Models\ExamResult;
use App\Models\Skill;
use App\Models\SkillScore;

class DomainComputationService
{
    /**
     * Compute psychomotor and affective domain scores for exam results.
     * 
     * This aggregates skill scores into overall domain scores for each student.
     */
    public function computeForExamClass(int $examId, int $classId, ?int $sectionId, ?int $academicYearId): void
    {
        $skillScores = SkillScore::query()
            ->with('skill')
            ->where('exam_id', $examId)
            ->where('class_id', $classId)
            ->when($sectionId, fn ($q) => $q->where('section_id', $sectionId))
            ->when($academicYearId, fn ($q) => $q->where('academic_year_id', $academicYearId))
            ->get();

        if ($skillScores->isEmpty()) {
            return;
        }

        $studentScores = $skillScores->groupBy('student_id');

        foreach ($studentScores as $studentId => $scores) {
            $psychomotorScores = $scores->filter(fn ($s) => $s->skill->skill_type === Skill::SKILL_TYPE_PSYCHOMOTOR);
            $affectiveScores = $scores->filter(fn ($s) => $s->skill->skill_type === Skill::SKILL_TYPE_AFFECTIVE);

            $psychomotorAverage = $psychomotorScores->isNotEmpty() 
                ? round($psychomotorScores->avg('rating'), 1) 
                : null;

            $affectiveAverage = $affectiveScores->isNotEmpty() 
                ? round($affectiveScores->avg('rating'), 1) 
                : null;

            // Convert to grade-like format (e.g., "4.5/5" or just "5")
            $psychomotorDisplay = $psychomotorAverage ? number_format($psychomotorAverage, 1) . '/5' : null;
            $affectiveDisplay = $affectiveAverage ? number_format($affectiveAverage, 1) . '/5' : null;

            ExamResult::updateOrCreate(
                [
                    'exam_id' => $examId,
                    'student_id' => $studentId,
                    'academic_year_id' => $academicYearId,
                ],
                [
                    'psychomotor' => $psychomotorDisplay,
                    'affective' => $affectiveDisplay,
                ]
            );
        }
    }

    /**
     * Get rating label for a numeric rating (1-5 scale).
     */
    public function getRatingLabel(int $rating): string
    {
        return match ($rating) {
            5 => 'Excellent',
            4 => 'Good',
            3 => 'Satisfactory',
            2 => 'Needs Improvement',
            1 => 'Poor',
            default => 'Not Rated',
        };
    }
}
