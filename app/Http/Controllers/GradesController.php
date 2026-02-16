<?php

namespace App\Http\Controllers;

use App\Models\ClassLevel;
use App\Models\Grade;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GradesController extends Controller
{
    public function index()
    {
        return Inertia::render('Grades/Index', [
            'grades' => Grade::query()->with('classLevel')->orderBy('mark_from')->get(),
            'levels' => ClassLevel::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:40'],
            'mark_from' => ['required', 'integer', 'min:0', 'max:100'],
            'mark_to' => ['required', 'integer', 'min:0', 'max:100'],
            'remark' => ['nullable', 'string', 'max:80'],
            'class_level_id' => ['nullable', 'integer', 'exists:class_levels,id'],
        ]);

        Grade::create($data);

        return back();
    }

    public function destroy(Grade $grade)
    {
        $grade->delete();

        return back();
    }

    public function update(Request $request, Grade $grade)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:40'],
            'mark_from' => ['required', 'integer', 'min:0', 'max:100'],
            'mark_to' => ['required', 'integer', 'min:0', 'max:100'],
            'remark' => ['nullable', 'string', 'max:80'],
            'class_level_id' => ['nullable', 'integer', 'exists:class_levels,id'],
        ]);

        $grade->update($data);

        return back();
    }
}
