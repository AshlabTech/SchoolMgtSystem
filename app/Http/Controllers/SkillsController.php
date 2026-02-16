<?php

namespace App\Http\Controllers;

use App\Models\ClassLevel;
use App\Models\Skill;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SkillsController extends Controller
{
    public function index()
    {
        return Inertia::render('Skills/Index', [
            'skills' => Skill::query()->with('classLevel')->orderBy('skill_type')->orderBy('name')->get(),
            'levels' => ClassLevel::query()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'skill_type' => ['required', 'string', 'in:psychomotor,affective'],
            'class_level_id' => ['nullable', 'integer', 'exists:class_levels,id'],
        ]);

        Skill::create($data);

        return back();
    }

    public function update(Request $request, Skill $skill)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'skill_type' => ['required', 'string', 'in:psychomotor,affective'],
            'class_level_id' => ['nullable', 'integer', 'exists:class_levels,id'],
        ]);

        $skill->update($data);

        return back();
    }

    public function destroy(Skill $skill)
    {
        $skill->delete();

        return back();
    }
}
