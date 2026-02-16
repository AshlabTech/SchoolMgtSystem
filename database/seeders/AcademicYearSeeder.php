<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AcademicYearSeeder extends Seeder
{
    public function run(): void
    {
        if (AcademicYear::query()->exists()) {
            return;
        }

        $now = Carbon::now();
        $startYear = $now->month < 8 ? $now->year - 1 : $now->year;
        $endYear = $startYear + 1;

        AcademicYear::create([
            'name' => $startYear.'/'.$endYear,
            'starts_at' => Carbon::create($startYear, 9, 1),
            'ends_at' => Carbon::create($endYear, 6, 30),
            'is_current' => true,
        ]);
    }
}
