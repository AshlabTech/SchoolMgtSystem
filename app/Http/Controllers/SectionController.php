<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'class_id' => ['required', 'integer', 'exists:classes,id'],
            'teacher_id' => ['nullable', 'integer', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        Section::create([
            'class_id' => $data['class_id'],
            'teacher_id' => $data['teacher_id'] ?? null,
            'name' => $data['name'],
            'is_active' => true,
        ]);

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
            'class_id' => ['required', 'integer', 'exists:classes,id'],
            'teacher_id' => ['nullable', 'integer', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        $section->update([
            'class_id' => $data['class_id'],
            'teacher_id' => $data['teacher_id'] ?? null,
            'name' => $data['name'],
        ]);

        return back();
    }
}
