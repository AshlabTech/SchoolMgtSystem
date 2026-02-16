<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'class_id' => ['nullable', 'integer', 'exists:classes,id'],
            'class_ids' => ['nullable', 'array'],
            'class_ids.*' => ['integer', 'exists:classes,id'],
            'teacher_id' => ['nullable', 'integer', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        $classIds = $data['class_ids'] ?? [];
        if (empty($classIds) && !empty($data['class_id'])) {
            $classIds = [$data['class_id']];
        }

        if (empty($classIds)) {
            return back()->withErrors([
                'class_ids' => 'Select at least one class.',
                'class_id' => 'Select at least one class.',
            ]);
        }

        $section = Section::create([
            'class_id' => $classIds[0] ?? null,
            'teacher_id' => $data['teacher_id'] ?? null,
            'name' => $data['name'],
            'is_active' => true,
        ]);

        $section->schoolClasses()->sync($classIds);

        return back();
    }

    public function destroy(Section $section)
    {
        $section->delete();

        return back();
    }

    public function update(Request $request, Section $section)
    {
        $data = $request->validate([
            'class_id' => ['nullable', 'integer', 'exists:classes,id'],
            'class_ids' => ['nullable', 'array'],
            'class_ids.*' => ['integer', 'exists:classes,id'],
            'teacher_id' => ['nullable', 'integer', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        $classIds = $data['class_ids'] ?? [];
        if (empty($classIds) && !empty($data['class_id'])) {
            $classIds = [$data['class_id']];
        }

        if (empty($classIds)) {
            return back()->withErrors([
                'class_ids' => 'Select at least one class.',
                'class_id' => 'Select at least one class.',
            ]);
        }

        $section->update([
            'class_id' => $classIds[0] ?? null,
            'teacher_id' => $data['teacher_id'] ?? null,
            'name' => $data['name'],
        ]);

        $section->schoolClasses()->sync($classIds);

        return back();
    }
}
