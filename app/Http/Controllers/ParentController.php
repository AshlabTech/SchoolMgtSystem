<?php

namespace App\Http\Controllers;

use App\Models\StudentGuardian;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ParentController extends Controller
{
    public function index()
    {
        $children = StudentGuardian::with(['student.user', 'student.currentEnrollment.schoolClass', 'student.currentEnrollment.section'])
            ->where('guardian_user_id', Auth::id())
            ->get();

        return Inertia::render('Parents/Index', [
            'children' => $children,
        ]);
    }
}
