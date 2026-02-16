<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

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

        $classIds = collect($data['class_ids'] ?? [])
            ->when(!empty($data['class_id']), fn ($ids) => $ids->push($data['class_id']))
            ->filter()
            ->unique()
            ->values()
            ->all();

        if (empty($classIds)) {
            return back()->withErrors([
                'class_ids' => 'Select at least one class.',
                'class_id' => 'Select at least one class.',
            ]);
        }

        $hasPivot = Schema::hasTable('class_section');
        if (!$hasPivot && count($classIds) > 1) {
            return back()->withErrors([
                'class_ids' => 'Run migrations to enable multiple classes per section.',
            ]);
        }

        $section = Section::create([
            'class_id' => $classIds[0],
            'teacher_id' => $data['teacher_id'] ?? null,
            'name' => $data['name'],
            'is_active' => true,
        ]);

        if ($hasPivot) {
            $section->schoolClasses()->sync($classIds);
        }

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

        $classIds = collect($data['class_ids'] ?? [])
            ->when(!empty($data['class_id']), fn ($ids) => $ids->push($data['class_id']))
            ->filter()
            ->unique()
            ->values()
            ->all();

        if (empty($classIds)) {
            return back()->withErrors([
                'class_ids' => 'Select at least one class.',
                'class_id' => 'Select at least one class.',
            ]);
        }

        $hasPivot = Schema::hasTable('class_section');
        if (!$hasPivot && count($classIds) > 1) {
            return back()->withErrors([
                'class_ids' => 'Run migrations to enable multiple classes per section.',
            ]);
        }

        $section->update([
            'class_id' => $classIds[0],
            'teacher_id' => $data['teacher_id'] ?? null,
            'name' => $data['name'],
        ]);

        if ($hasPivot) {
            $section->schoolClasses()->sync($classIds);
        }

        return back();
    }
}
