<?php

namespace Database\Seeders;

use App\Models\ClassLevel;
use App\Models\SchoolClass;
use Illuminate\Database\Seeder;

class SchoolClassesSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            [
                'name' => 'Nursery',
                'code' => 'NUR',
                'school_type' => 'primary',
                'classes' => ['Creche', 'Nursery 1', 'Nursery 2'],
            ],
            [
                'name' => 'Primary',
                'code' => 'PRI',
                'school_type' => 'primary',
                'classes' => ['Primary 1', 'Primary 2', 'Primary 3', 'Primary 4', 'Primary 5', 'Primary 6'],
            ],
            [
                'name' => 'Secondary',
                'code' => 'SEC',
                'school_type' => 'secondary',
                'classes' => ['JSS 1', 'JSS 2', 'JSS 3', 'SS 1', 'SS 2', 'SS 3'],
            ],
        ];

        foreach ($levels as $levelData) {
            $level = ClassLevel::firstOrCreate(
                ['name' => $levelData['name']],
                [
                    'code' => $levelData['code'],
                    'description' => $levelData['name'].' classes',
                    'school_type' => $levelData['school_type'],
                    'is_active' => true,
                ]
            );

            foreach ($levelData['classes'] as $className) {
                SchoolClass::firstOrCreate(
                    ['class_level_id' => $level->id, 'name' => $className],
                    [
                        'code' => str($className)->upper()->replace(' ', '')->value(),
                        'description' => $className,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
