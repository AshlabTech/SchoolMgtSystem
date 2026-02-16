<?php

namespace App\Http\Controllers;

use App\Models\FeeRecord;
use App\Models\LibraryLoan;
use App\Models\Mark;
use App\Models\Timetable;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class StudentPortalController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load(['student.user.profile', 'student.currentEnrollment.schoolClass', 'student.currentEnrollment.section']);
        $student = $user->student;

        if (!$student) {
            abort(404, 'Student profile not found.');
        }

        $marks = Mark::with(['subject', 'exam', 'grade'])
            ->where('student_id', $student->id)
            ->orderByDesc('exam_id')
            ->limit(50)
            ->get();

        $feeRecords = FeeRecord::with('feeDefinition')
            ->where('student_id', $student->id)
            ->orderByDesc('created_at')
            ->get();

        $loans = LibraryLoan::with('book')
            ->where('borrower_user_id', $user->id)
            ->orderByDesc('issued_at')
            ->get();

        $timetables = [];
        $enrollment = $student->currentEnrollment;

        if ($enrollment) {
            $timetables = Timetable::with(['entries.timeslot', 'entries.subject'])
                ->where('class_id', $enrollment->class_id)
                ->when($enrollment->section_id, fn ($q) => $q->where('section_id', $enrollment->section_id))
                ->where('type', 'class')
                ->orderByDesc('created_at')
                ->get();
        }

        return Inertia::render('Portals/Student', [
            'student' => $student,
            'marks' => $marks,
            'feeRecords' => $feeRecords,
            'loans' => $loans,
            'timetables' => $timetables,
        ]);
    }
}
