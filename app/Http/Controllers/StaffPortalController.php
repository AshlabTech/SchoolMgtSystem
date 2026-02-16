<?php

namespace App\Http\Controllers;

use App\Models\SubjectAssignment;
use App\Models\TimetableEntry;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class StaffPortalController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load(['roles', 'profile', 'staffProfile']);

        $assignments = SubjectAssignment::with(['subject', 'schoolClass', 'section'])
            ->where('teacher_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        $timetableEntries = TimetableEntry::with(['subject', 'timeslot', 'timetable.schoolClass', 'timetable.section'])
            ->where('teacher_id', $user->id)
            ->orderByDesc('created_at')
            ->limit(40)
            ->get();

        return Inertia::render('Portals/Staff', [
            'user' => $user,
            'assignments' => $assignments,
            'timetableEntries' => $timetableEntries,
        ]);
    }
}
