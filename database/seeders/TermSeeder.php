<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Term;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TermSeeder extends Seeder
{
    public function run(): void
    {
        $academicYear = AcademicYear::query()
            ->where('is_current', true)
            ->first() ?? AcademicYear::query()->first();

        if (!$academicYear) {
            return;
        }

        if (Term::query()->where('academic_year_id', $academicYear->id)->exists()) {
            return;
        }

        $start = $academicYear->starts_at ? Carbon::parse($academicYear->starts_at)->startOfDay() : null;
        $end = $academicYear->ends_at ? Carbon::parse($academicYear->ends_at)->endOfDay() : null;
        $now = Carbon::now();

        $terms = [
            ['name' => 'First Term', 'order' => 1],
            ['name' => 'Second Term', 'order' => 2],
            ['name' => 'Third Term', 'order' => 3],
        ];

        if ($start) {
            $term1Start = $start->copy();
            $term1End = $start->copy()->addMonthsNoOverflow(3)->subDay();
            $term2Start = $term1End->copy()->addDay();
            $term2End = $term2Start->copy()->addMonthsNoOverflow(3)->subDay();
            $term3Start = $term2End->copy()->addDay();
            $term3End = $end ?? $term3Start->copy()->addMonthsNoOverflow(3)->subDay();

            $terms[0]['starts_at'] = $term1Start->toDateString();
            $terms[0]['ends_at'] = $term1End->toDateString();
            $terms[1]['starts_at'] = $term2Start->toDateString();
            $terms[1]['ends_at'] = $term2End->toDateString();
            $terms[2]['starts_at'] = $term3Start->toDateString();
            $terms[2]['ends_at'] = $term3End->toDateString();
        }

        $hasCurrent = false;
        foreach ($terms as $term) {
            $isCurrent = false;
            if (!empty($term['starts_at']) && !empty($term['ends_at'])) {
                $isCurrent = $now->between($term['starts_at'], $term['ends_at']);
            }
            if ($isCurrent) {
                $hasCurrent = true;
            }

            Term::create([
                'academic_year_id' => $academicYear->id,
                'name' => $term['name'],
                'order' => $term['order'],
                'starts_at' => $term['starts_at'] ?? null,
                'ends_at' => $term['ends_at'] ?? null,
                'is_current' => $isCurrent,
            ]);
        }

        if (!$hasCurrent) {
            Term::where('academic_year_id', $academicYear->id)
                ->where('order', 1)
                ->update(['is_current' => true]);
        }
    }
}
