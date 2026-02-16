<?php

namespace App\Http\Controllers;

use App\Models\SubjectAssignment;
use App\Models\TimetableEntry;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class StaffPortalController extends Controller
{
    public function index(Request $request)
    {
        $authUser = Auth::user();
        $isAdmin = $authUser?->hasAnyRole(['admin', 'super_admin']);
        $selectedStaffId = $request->integer('staff_id') ?: null;

        if ($isAdmin) {
            if ($selectedStaffId) {
                $user = User::with(['roles', 'profile', 'staffProfile'])->find($selectedStaffId);
                if (!$user) {
                    abort(404, 'Staff not found.');
                }
            } else {
                $user = null;
            }
        } else {
            $user = $authUser?->load(['roles', 'profile', 'staffProfile']);
        }

        $assignments = [];
        $timetableEntries = [];

        if ($user) {
            $assignments = SubjectAssignment::with(['subject', 'schoolClass', 'section'])
                ->where('teacher_id', $user->id)
                ->orderByDesc('created_at')
                ->get();

            $timetableEntries = TimetableEntry::with(['subject', 'timeslot', 'timetable.schoolClass', 'timetable.section'])
                ->where('teacher_id', $user->id)
                ->orderByDesc('created_at')
                ->limit(40)
                ->get();
        }

        return Inertia::render('Portals/Staff', [
            'user' => $user,
            'assignments' => $assignments,
            'timetableEntries' => $timetableEntries,
            'adminView' => $isAdmin,
            'staffOptions' => $isAdmin
                ? User::query()
                    ->with('profile')
                    ->whereHas('staffProfile')
                    ->orderBy('name')
                    ->get()
                    ->map(function ($item) {
                        $label = trim(($item->profile?->first_name ?? '').' '.($item->profile?->last_name ?? ''));
                        if ($label === '') {
                            $label = $item->name ?? $item->email ?? ('User #'.$item->id);
                        }
                        return [
                            'label' => $label,
                            'value' => $item->id,
                        ];
                    })
                : [],
            'selectedStaffId' => $selectedStaffId,
        ]);
    }
}
