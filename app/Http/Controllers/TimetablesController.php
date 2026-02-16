<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\ClassLevel;
use App\Models\Exam;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\SubjectAssignment;
use App\Models\Timetable;
use App\Models\TimetableEntry;
use App\Models\Timeslot;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TimetablesController extends Controller
{
    public function index()
    {
        return Inertia::render('Timetables/Index', [
            'timetables' => Timetable::with(['schoolClass', 'section', 'academicYear', 'term', 'exam'])
                ->orderByDesc('created_at')
                ->get(),
            'classes' => SchoolClass::with('level')->orderBy('name')->get(),
            'classLevels' => ClassLevel::orderBy('name')->get(),
            'sections' => Section::orderBy('name')->get(),
            'academicYears' => AcademicYear::orderByDesc('name')->get(),
            'terms' => \App\Models\Term::orderBy('order')->get(),
            'exams' => Exam::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'class_id' => ['required', 'exists:classes,id'],
            'section_id' => ['nullable', 'exists:sections,id'],
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'term_id' => ['nullable', 'exists:terms,id'],
            'exam_id' => ['nullable', 'exists:exams,id'],
            'type' => ['required', 'in:class,exam'],
        ]);

        if ($data['type'] === 'exam' && empty($data['exam_id'])) {
            return back()->withErrors(['exam_id' => 'Exam timetable requires an exam.']);
        }

        Timetable::create($data);

        return back();
    }

    public function update(Request $request, Timetable $timetable)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'class_id' => ['required', 'exists:classes,id'],
            'section_id' => ['nullable', 'exists:sections,id'],
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'term_id' => ['nullable', 'exists:terms,id'],
            'exam_id' => ['nullable', 'exists:exams,id'],
            'type' => ['required', 'in:class,exam'],
        ]);

        if ($data['type'] === 'exam' && empty($data['exam_id'])) {
            return back()->withErrors(['exam_id' => 'Exam timetable requires an exam.']);
        }

        $timetable->update($data);

        return back();
    }

    public function show(Timetable $timetable)
    {
        $subjectIds = SubjectAssignment::where('class_id', $timetable->class_id)->pluck('subject_id');

        return Inertia::render('Timetables/Manage', [
            'timetable' => $timetable->load(['schoolClass', 'section', 'academicYear', 'term', 'exam']),
            'timeslots' => Timeslot::where('timetable_id', $timetable->id)->orderBy('sort_order')->get(),
            'entries' => TimetableEntry::with(['subject', 'timeslot'])
                ->where('timetable_id', $timetable->id)
                ->get(),
            'subjects' => \App\Models\Subject::whereIn('id', $subjectIds)->orderBy('name')->get(),
        ]);
    }

    public function storeTimeslot(Request $request, Timetable $timetable)
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:100'],
            'time_from' => ['required', 'date_format:H:i'],
            'time_to' => ['required', 'date_format:H:i'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['timetable_id'] = $timetable->id;

        Timeslot::create($data);

        return back();
    }

    public function destroyTimeslot(Timeslot $timeslot)
    {
        $timeslot->delete();
        return back();
    }

    public function updateTimeslot(Request $request, Timeslot $timeslot)
    {
        $data = $request->validate([
            'label' => ['required', 'string', 'max:100'],
            'time_from' => ['required', 'date_format:H:i'],
            'time_to' => ['required', 'date_format:H:i'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $timeslot->update($data);

        return back();
    }

    public function storeEntry(Request $request, Timetable $timetable)
    {
        $rules = [
            'timeslot_id' => ['required', 'exists:timeslots,id'],
            'subject_id' => ['nullable', 'exists:subjects,id'],
            'teacher_id' => ['nullable', 'exists:users,id'],
            'room' => ['nullable', 'string', 'max:100'],
        ];

        if ($timetable->type === 'exam') {
            $rules['exam_date'] = ['required', 'date'];
        } else {
            $rules['day_of_week'] = ['required', 'integer', 'min:0', 'max:6'];
        }

        $data = $request->validate($rules);
        $data['timetable_id'] = $timetable->id;

        $unique = [
            'timetable_id' => $timetable->id,
            'timeslot_id' => $data['timeslot_id'],
            'day_of_week' => $data['day_of_week'] ?? null,
            'exam_date' => $data['exam_date'] ?? null,
        ];

        TimetableEntry::updateOrCreate(
            $unique,
            [
                'subject_id' => $data['subject_id'] ?? null,
                'teacher_id' => $data['teacher_id'] ?? null,
                'room' => $data['room'] ?? null,
            ]
        );

        return back();
    }

    public function destroyEntry(TimetableEntry $entry)
    {
        $entry->delete();
        return back();
    }

    public function updateEntry(Request $request, TimetableEntry $entry)
    {
        $timetable = $entry->timetable;
        $rules = [
            'timeslot_id' => ['required', 'exists:timeslots,id'],
            'subject_id' => ['nullable', 'exists:subjects,id'],
            'teacher_id' => ['nullable', 'exists:users,id'],
            'room' => ['nullable', 'string', 'max:100'],
        ];

        if ($timetable && $timetable->type === 'exam') {
            $rules['exam_date'] = ['required', 'date'];
        } else {
            $rules['day_of_week'] = ['required', 'integer', 'min:0', 'max:6'];
        }

        $data = $request->validate($rules);
        $entry->update([
            'timeslot_id' => $data['timeslot_id'],
            'subject_id' => $data['subject_id'] ?? null,
            'teacher_id' => $data['teacher_id'] ?? null,
            'room' => $data['room'] ?? null,
            'day_of_week' => $data['day_of_week'] ?? null,
            'exam_date' => $data['exam_date'] ?? null,
        ]);

        return back();
    }
}
