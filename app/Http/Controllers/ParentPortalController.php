<?php

namespace App\Http\Controllers;

use App\Models\FeeRecord;
use App\Models\Mark;
use App\Models\StudentGuardian;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ParentPortalController extends Controller
{
    public function index()
    {
        $guardianId = Auth::id();

        $children = StudentGuardian::with([
            'student.user.profile',
            'student.currentEnrollment.schoolClass',
            'student.currentEnrollment.section',
        ])
            ->where('guardian_user_id', $guardianId)
            ->get();

        $studentIds = $children->pluck('student_id')->filter();

        $marks = Mark::with(['subject', 'exam', 'grade'])
            ->whereIn('student_id', $studentIds)
            ->orderByDesc('exam_id')
            ->get()
            ->groupBy('student_id');

        $feeRecords = FeeRecord::with('feeDefinition')
            ->whereIn('student_id', $studentIds)
            ->orderByDesc('created_at')
            ->get()
            ->groupBy('student_id');

        return Inertia::render('Portals/Parent', [
            'children' => $children,
            'marks' => $marks,
            'feeRecords' => $feeRecords,
        ]);
    }
}
