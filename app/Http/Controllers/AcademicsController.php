<?php

namespace App\Http\Controllers;

use App\Models\ClassLevel;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\User;
use Inertia\Inertia;

class AcademicsController extends Controller
{
    public function index()
    {
        return Inertia::render('Academics/Index', [
            'classLevels' => ClassLevel::query()
                ->withCount('classes')
                ->orderBy('name')
                ->get(),
            'classes' => SchoolClass::query()
                ->with(['level'])
                ->withCount('sections')
                ->orderBy('name')
                ->get(),
            'sections' => Section::query()
                ->with(['schoolClass', 'schoolClasses', 'teacher'])
                ->orderBy('name')
                ->get(),
            'teachers' => User::role(['teacher', 'form_teacher'])
                ->with('profile')
                ->orderBy('name')
                ->get(),
        ]);
    }
}
