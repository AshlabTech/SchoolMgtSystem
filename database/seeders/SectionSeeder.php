<?php

namespace Database\Seeders;

use App\Models\SchoolClass;
use App\Models\Section;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class SectionSeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            'Nursery' => ['Creche', 'Nursery 1', 'Nursery 2'],
            'Primary' => ['Primary 1', 'Primary 2', 'Primary 3', 'Primary 4', 'Primary 5', 'Primary 6'],
            'Secondary' => ['JSS 1', 'JSS 2', 'JSS 3', 'SS 1', 'SS 2', 'SS 3'],
        ];

        foreach ($groups as $sectionName => $classNames) {
            $classes = SchoolClass::query()
                ->whereIn('name', $classNames)
                ->orderBy('id')
                ->get();

            if ($classes->isEmpty()) {
                continue;
            }

            $section = Section::firstOrCreate(
                ['name' => $sectionName, 'class_id' => $classes->first()->id],
                ['is_active' => true]
            );

            if (Schema::hasTable('class_section')) {
                $section->schoolClasses()->syncWithoutDetaching($classes->pluck('id')->all());
            }
        }
    }
}
