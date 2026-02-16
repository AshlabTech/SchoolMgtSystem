<?php

namespace App\Http\Controllers;

use App\Models\FeeRecord;
use App\Models\Mark;
use App\Models\StudentGuardian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ParentPortalController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = $user?->hasAnyRole(['admin', 'super_admin']);
        $selectedParentId = $request->integer('guardian_id') ?: null;
        $guardianId = null;
        if ($isAdmin) {
            $guardianId = $selectedParentId ?: null;
        } else {
            $guardianId = $user?->id;
        }

        $children = $guardianId
            ? StudentGuardian::with([
                'student.user.profile',
                'student.currentEnrollment.schoolClass',
                'student.currentEnrollment.section',
            ])
                ->where('guardian_user_id', $guardianId)
                ->get()
            : collect([]);

        $studentIds = $children->pluck('student_id')->filter();

        $marks = $studentIds->isEmpty()
            ? collect([])
            : Mark::with(['subject', 'exam', 'grade'])
                ->whereIn('student_id', $studentIds)
                ->orderByDesc('exam_id')
                ->get()
                ->groupBy('student_id');

        $feeRecords = $studentIds->isEmpty()
            ? collect([])
            : FeeRecord::with('invoiceType')
                ->whereIn('student_id', $studentIds)
                ->orderByDesc('created_at')
                ->get()
                ->groupBy('student_id');

        return Inertia::render('Portals/Parent', [
            'children' => $children,
            'marks' => $marks,
            'feeRecords' => $feeRecords,
            'adminView' => $isAdmin,
            'parentOptions' => $isAdmin
                ? User::role('parent')
                    ->with('profile')
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
            'selectedParentId' => $selectedParentId,
        ]);
    }
}
