<?php

namespace App\Http\Controllers;

use App\Models\ClassLevel;
use Illuminate\Http\Request;

class ClassLevelController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:class_levels,name'],
            'code' => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        ClassLevel::create($data);

        return back();
    }

    public function destroy(ClassLevel $classLevel)
    {
        $classLevel->delete();

        return back();
    }

    public function update(Request $request, ClassLevel $classLevel)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:class_levels,name,'.$classLevel->id],
            'code' => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $classLevel->update($data);

        return back();
    }
}
