<?php

namespace App\Http\Controllers;

use App\Models\FeeRecord;
use App\Models\LibraryLoan;
use App\Models\Mark;
use App\Models\Student;
use App\Models\Timetable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class StudentPortalController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user?->hasAnyRole(['admin', 'super_admin']);
        $selectedStudentId = $request->integer('student_id') ?: null;

        if ($isAdmin) {
            if ($selectedStudentId) {
                $student = Student::query()
                    ->with(['user.profile', 'currentEnrollment.schoolClass', 'currentEnrollment.section'])
                    ->find($selectedStudentId);
                if (!$student) {
                    abort(404, 'Student not found.');
                }
                $borrowerUserId = $student->user_id;
            } else {
                $student = null;
                $borrowerUserId = null;
            }
        } else {
            $user?->load(['student.user.profile', 'student.currentEnrollment.schoolClass', 'student.currentEnrollment.section']);
            $student = $user?->student;
            $borrowerUserId = $user?->id;
        }

        if (!$student && !$isAdmin) {
            abort(404, 'Student profile not found.');
        }

        $marks = [];
        $feeRecords = [];
        $loans = [];
        $timetables = [];

        if ($student) {
            $marks = Mark::with(['subject', 'exam', 'grade'])
                ->where('student_id', $student->id)
                ->orderByDesc('exam_id')
                ->limit(50)
                ->get();

            $feeRecords = FeeRecord::with('invoiceType')
                ->where('student_id', $student->id)
                ->orderByDesc('created_at')
                ->get();

            $loans = LibraryLoan::with('book')
                ->where('borrower_user_id', $borrowerUserId)
                ->orderByDesc('issued_at')
                ->get();

            $enrollment = $student->currentEnrollment;

            if ($enrollment) {
                $timetables = Timetable::with(['entries.timeslot', 'entries.subject'])
                    ->where('class_id', $enrollment->class_id)
                    ->when($enrollment->section_id, fn ($q) => $q->where('section_id', $enrollment->section_id))
                    ->where('type', 'class')
                    ->orderByDesc('created_at')
                    ->get();
            }
        }

        return Inertia::render('Portals/Student', [
            'student' => $student,
            'marks' => $marks,
            'feeRecords' => $feeRecords,
            'loans' => $loans,
            'timetables' => $timetables,
            'adminView' => $isAdmin,
            'studentOptions' => $isAdmin
                ? Student::with('user.profile')
                    ->orderBy('admission_no')
                    ->get()
                    ->map(function ($item) {
                        $label = trim(($item->admission_no ? $item->admission_no.' - ' : '').($item->user?->profile?->first_name ?? '').' '.($item->user?->profile?->last_name ?? ''));
                        if ($label === '' || $label === '-') {
                            $label = $item->user?->name ?? $item->admission_no ?? ('Student #'.$item->id);
                        }
                        return [
                            'label' => $label,
                            'value' => $item->id,
                        ];
                    })
                : [],
            'selectedStudentId' => $selectedStudentId,
        ]);
    }
}
