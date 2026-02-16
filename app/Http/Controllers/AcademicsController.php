<?php

namespace App\Http\Controllers;

use App\Models\ClassLevel;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;

class AcademicsController extends Controller
{
    public function index()
    {
        $classQuery = SchoolClass::query()
            ->with(['level'])
            ->orderBy('name');
        if (Schema::hasTable('class_section')) {
            $classQuery->withCount('sections');
        } else {
            $classQuery->withCount(['legacySections as sections_count']);
        }

        $sectionQuery = Section::query()->with(['schoolClass', 'teacher']);
        if (Schema::hasTable('class_section')) {
            $sectionQuery->with('schoolClasses');
        }

        return Inertia::render('Academics/Index', [
            'classLevels' => ClassLevel::query()
                ->withCount('classes')
                ->orderBy('name')
                ->get(),
            'classes' => $classQuery->get(),
            'sections' => $sectionQuery
                ->orderBy('name')
                ->get(),
            'teachers' => User::role(['teacher', 'form_teacher'])
                ->with('profile')
                ->orderBy('name')
                ->get(),
        ]);
    }
}
