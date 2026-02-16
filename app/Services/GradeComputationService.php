<?php

namespace App\Services;

use App\Models\Grade;
use App\Models\Mark;
use App\Models\SchoolClass;
use App\Models\Setting;

class GradeComputationService
{
    /**
     * Compute TCA, total score, grade, and subject positions for a batch of marks.
     */
    public function computeForExamSubject(int $examId, int $subjectId, int $classId, ?int $sectionId, ?int $academicYearId): void
    {
        $settings = $this->getGradingSettings();

        $marks = Mark::query()
            ->where('exam_id', $examId)
            ->where('subject_id', $subjectId)
            ->where('class_id', $classId)
            ->when($sectionId, fn ($q) => $q->where('section_id', $sectionId))
            ->when($academicYearId, fn ($q) => $q->where('academic_year_id', $academicYearId))
            ->get();

        if ($marks->isEmpty()) {
            return;
        }

        $classLevelId = SchoolClass::find($classId)?->class_level_id;

        $grades = Grade::query()
            ->where(function ($q) use ($classLevelId) {
                $q->whereNull('class_level_id');
                if ($classLevelId) {
                    $q->orWhere('class_level_id', $classLevelId);
                }
            })
            ->orderByDesc('mark_from')
            ->get();

        foreach ($marks as $mark) {
            $tca = $this->computeTca($mark, $settings);
            $total = $tca + ($mark->exm ?? 0);

            $grade = $this->resolveGrade($total, $grades, $classLevelId);

            $mark->update([
                'tca' => $tca,
                'cum' => $total,
                'grade_id' => $grade?->id,
            ]);
        }

        if ($settings['auto_compute_subject_position']) {
            $this->computeSubjectPositions($examId, $subjectId, $classId, $sectionId, $academicYearId);
        }
    }

    /**
     * Compute the Total Continuous Assessment from CA component scores.
     */
    private function computeTca(Mark $mark, array $settings): int
    {
        $components = (int) $settings['number_of_ca_components'];
        $tca = 0;

        for ($i = 1; $i <= $components; $i++) {
            $field = "t{$i}";
            $tca += $mark->{$field} ?? 0;
        }

        return $tca;
    }

    /**
     * Resolve the grade for a total score based on grade definitions.
     */
    private function resolveGrade(int $total, $grades, ?int $classLevelId): ?Grade
    {
        $specificGrade = $grades
            ->where('class_level_id', $classLevelId)
            ->first(fn (Grade $g) => $total >= $g->mark_from && $total <= $g->mark_to);

        if ($specificGrade) {
            return $specificGrade;
        }

        return $grades
            ->whereNull('class_level_id')
            ->first(fn (Grade $g) => $total >= $g->mark_from && $total <= $g->mark_to);
    }

    /**
     * Compute subject-level positions (ranking) for students.
     */
    private function computeSubjectPositions(int $examId, int $subjectId, int $classId, ?int $sectionId, ?int $academicYearId): void
    {
        $marks = Mark::query()
            ->where('exam_id', $examId)
            ->where('subject_id', $subjectId)
            ->where('class_id', $classId)
            ->when($sectionId, fn ($q) => $q->where('section_id', $sectionId))
            ->when($academicYearId, fn ($q) => $q->where('academic_year_id', $academicYearId))
            ->orderByDesc('cum')
            ->get();

        $position = 0;
        $lastScore = null;
        $skipCount = 0;

        foreach ($marks as $mark) {
            if ($mark->cum !== $lastScore) {
                $position += 1 + $skipCount;
                $skipCount = 0;
            } else {
                $skipCount++;
            }

            $mark->update(['sub_pos' => $position]);
            $lastScore = $mark->cum;
        }
    }

    /**
     * Get grading-related settings.
     */
    private function getGradingSettings(): array
    {
        $settings = Setting::query()
            ->whereIn('key', [
                'ca_total_weight',
                'exam_weight',
                'number_of_ca_components',
                'auto_compute_grade',
                'auto_compute_subject_position',
            ])
            ->pluck('value', 'key');

        return [
            'ca_total_weight' => (int) ($settings['ca_total_weight'] ?? 40),
            'exam_weight' => (int) ($settings['exam_weight'] ?? 60),
            'number_of_ca_components' => (int) ($settings['number_of_ca_components'] ?? 2),
            'auto_compute_grade' => (bool) ($settings['auto_compute_grade'] ?? true),
            'auto_compute_subject_position' => (bool) ($settings['auto_compute_subject_position'] ?? true),
        ];
    }
}
