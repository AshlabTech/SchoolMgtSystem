<?php

namespace App\Http\Controllers;

use App\Models\Lga;
use App\Models\Section;
use App\Models\SubjectAssignment;
use Illuminate\Http\JsonResponse;

class AjaxController extends Controller
{
    public function lgas(int $stateId): JsonResponse
    {
        $lgas = Lga::where('state_id', $stateId)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($lgas);
    }

    public function classSections(int $classId): JsonResponse
    {
        $sections = Section::query()
            ->forClass($classId)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($sections);
    }

    public function classSubjects(int $classId): JsonResponse
    {
        $subjectIds = SubjectAssignment::where('class_id', $classId)->pluck('subject_id');

        $subjects = \App\Models\Subject::whereIn('id', $subjectIds)
            ->orderBy('name')
            ->get(['id', 'name']);

        $sections = Section::query()
            ->forClass($classId)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json([
            'sections' => $sections,
            'subjects' => $subjects,
        ]);
    }
}
