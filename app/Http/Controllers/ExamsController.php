<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Exam;
use App\Models\Term;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExamsController extends Controller
{
    public function index()
    {
        return Inertia::render('Exams/Index', [
            'exams' => Exam::query()->with(['academicYear', 'term'])->orderByDesc('id')->get(),
            'years' => AcademicYear::query()->orderByDesc('name')->get(),
            'terms' => Term::query()->orderBy('order')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'academic_year_id' => ['nullable', 'integer', 'exists:academic_years,id'],
            'term_id' => ['nullable', 'integer', 'exists:terms,id'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date'],
        ]);

        Exam::create($data);

        return back();
    }

    public function destroy(Exam $exam)
    {
        $exam->delete();

        return back();
    }

    public function update(Request $request, Exam $exam)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'academic_year_id' => ['nullable', 'integer', 'exists:academic_years,id'],
            'term_id' => ['nullable', 'integer', 'exists:terms,id'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date'],
        ]);

        $exam->update($data);

        return back();
    }
}
