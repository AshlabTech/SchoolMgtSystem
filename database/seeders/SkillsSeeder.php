<?php

namespace Database\Seeders;

use App\Models\ClassLevel;
use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillsSeeder extends Seeder
{
    public function run(): void
    {
        $psychomotorSkills = [
            'Handwriting',
            'Drawing & Painting',
            'Sports & Games',
            'Verbal Fluency',
            'Tools Handling',
            'Practical Work',
            'Physical Fitness',
            'Musical Skills',
        ];

        $affectiveSkills = [
            'Punctuality',
            'Attendance',
            'Neatness',
            'Politeness',
            'Honesty',
            'Relationship with Others',
            'Self Control',
            'Perseverance',
            'Leadership',
            'Teamwork',
            'Attentiveness',
            'Emotional Stability',
        ];

        // Get all class levels or create null for all levels
        $classLevels = ClassLevel::all();

        // Create psychomotor skills
        foreach ($psychomotorSkills as $skillName) {
            Skill::firstOrCreate(
                [
                    'name' => $skillName,
                    'skill_type' => Skill::SKILL_TYPE_PSYCHOMOTOR,
                ],
                [
                    'class_level_id' => null, // Applies to all levels
                ]
            );
        }

        // Create affective skills
        foreach ($affectiveSkills as $skillName) {
            Skill::firstOrCreate(
                [
                    'name' => $skillName,
                    'skill_type' => Skill::SKILL_TYPE_AFFECTIVE,
                ],
                [
                    'class_level_id' => null, // Applies to all levels
                ]
            );
        }
    }
}
