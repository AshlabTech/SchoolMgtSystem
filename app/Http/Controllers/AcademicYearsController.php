<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use Illuminate\Http\Request;

class AcademicYearsController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120', 'unique:academic_years,name'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date'],
            'is_current' => ['nullable', 'boolean'],
        ]);

        if (!empty($data['is_current'])) {
            AcademicYear::query()->update(['is_current' => false]);
        }

        AcademicYear::create([
            'name' => $data['name'],
            'starts_at' => $data['starts_at'] ?? null,
            'ends_at' => $data['ends_at'] ?? null,
            'is_current' => (bool) ($data['is_current'] ?? false),
        ]);

        return back();
    }
}
