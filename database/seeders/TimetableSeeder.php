<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Term;
use App\Models\Timetable;
use App\Models\TimetableEntry;
use App\Models\Timeslot;
use Illuminate\Database\Seeder;

class TimetableSeeder extends Seeder
{
    public function run(): void
    {
        $academicYear = AcademicYear::query()->where('is_current', true)->first() ?? AcademicYear::query()->latest('id')->first();
        if (!$academicYear) {
            return;
        }

        $term = Term::query()
            ->where('academic_year_id', $academicYear->id)
            ->where('is_current', true)
            ->first() ?? Term::query()->where('academic_year_id', $academicYear->id)->orderBy('order')->first();

        $subjects = Subject::query()->orderBy('name')->take(3)->get();
        if ($subjects->isEmpty()) {
            return;
        }

        foreach (['Primary 1', 'JSS 1'] as $className) {
            $class = SchoolClass::query()->where('name', $className)->first();
            if (!$class) {
                continue;
            }

            $sectionId = Section::query()->forClass($class->id)->value('id');

            $timetable = Timetable::firstOrCreate(
                [
                    'class_id' => $class->id,
                    'section_id' => $sectionId,
                    'academic_year_id' => $academicYear->id,
                    'term_id' => $term?->id,
                    'exam_id' => null,
                    'type' => 'class',
                ],
                [
                    'name' => $className.' Weekly Timetable',
                ]
            );

            $slotRows = [
                ['label' => 'Period 1', 'time_from' => '08:00:00', 'time_to' => '08:40:00', 'sort_order' => 1],
                ['label' => 'Period 2', 'time_from' => '08:40:00', 'time_to' => '09:20:00', 'sort_order' => 2],
                ['label' => 'Period 3', 'time_from' => '09:40:00', 'time_to' => '10:20:00', 'sort_order' => 3],
            ];

            foreach ($slotRows as $slotIndex => $slotData) {
                $timeslot = Timeslot::firstOrCreate(
                    [
                        'timetable_id' => $timetable->id,
                        'time_from' => $slotData['time_from'],
                        'time_to' => $slotData['time_to'],
                    ],
                    [
                        'label' => $slotData['label'],
                        'sort_order' => $slotData['sort_order'],
                    ]
                );

                TimetableEntry::updateOrCreate(
                    [
                        'timetable_id' => $timetable->id,
                        'timeslot_id' => $timeslot->id,
                        'day_of_week' => 1,
                    ],
                    [
                        'subject_id' => $subjects[$slotIndex % $subjects->count()]->id,
                        'teacher_id' => null,
                        'room' => 'Room '.($slotIndex + 1),
                    ]
                );
            }
        }
    }
}
