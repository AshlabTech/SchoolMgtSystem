<?php

namespace App\Http\Controllers;

use App\Models\Term;
use Illuminate\Http\Request;

class TermsController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'name' => ['required', 'string', 'max:120'],
            'order' => ['required', 'integer', 'min:1'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date'],
            'is_current' => ['nullable', 'boolean'],
        ]);

        if (!empty($data['is_current'])) {
            Term::where('academic_year_id', $data['academic_year_id'])->update(['is_current' => false]);
        }

        Term::create([
            'academic_year_id' => $data['academic_year_id'],
            'name' => $data['name'],
            'order' => $data['order'],
            'starts_at' => $data['starts_at'] ?? null,
            'ends_at' => $data['ends_at'] ?? null,
            'is_current' => (bool) ($data['is_current'] ?? false),
        ]);

        return back();
    }
}
