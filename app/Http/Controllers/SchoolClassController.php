<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SchoolClassController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'class_level_id' => ['required', 'integer', 'exists:class_levels,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $request->validate([
            'name' => [
                Rule::unique('classes')->where('class_level_id', $data['class_level_id']),
            ],
        ]);

        SchoolClass::create($data);

        return back();
    }

    public function destroy(SchoolClass $schoolClass)
    {
        $schoolClass->delete();

        return back();
    }

    public function update(Request $request, SchoolClass $schoolClass)
    {
        $data = $request->validate([
            'class_level_id' => ['required', 'integer', 'exists:class_levels,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $request->validate([
            'name' => [
                Rule::unique('classes')
                    ->where('class_level_id', $data['class_level_id'])
                    ->ignore($schoolClass->id),
            ],
        ]);

        $schoolClass->update($data);

        return back();
    }
}
