<?php

namespace App\Http\Controllers;

use App\Models\DormAssignment;
use App\Models\Dormitory;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DormsController extends Controller
{
    public function index()
    {
        return Inertia::render('Dorms/Index', [
            'dorms' => Dormitory::withCount(['assignments' => function ($query) {
                $query->where('is_current', true);
            }])->orderBy('name')->get(),
            'assignments' => DormAssignment::with(['dormitory', 'student.user'])
                ->where('is_current', true)
                ->orderByDesc('created_at')
                ->get(),
            'students' => Student::with('user')->orderBy('created_at')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string'],
            'capacity' => ['nullable', 'integer', 'min:0'],
        ]);

        Dormitory::create($data);

        return back();
    }

    public function destroy(Dormitory $dormitory)
    {
        $dormitory->delete();
        return back();
    }

    public function update(Request $request, Dormitory $dormitory)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string'],
            'capacity' => ['nullable', 'integer', 'min:0'],
        ]);

        $dormitory->update($data);

        return back();
    }

    public function assign(Request $request)
    {
        $data = $request->validate([
            'dormitory_id' => ['required', 'exists:dormitories,id'],
            'student_id' => ['required', 'exists:students,id'],
            'room_no' => ['nullable', 'string', 'max:50'],
            'assigned_at' => ['nullable', 'date'],
        ]);

        DormAssignment::where('student_id', $data['student_id'])
            ->where('is_current', true)
            ->update([
                'is_current' => false,
                'released_at' => Carbon::now(),
            ]);

        DormAssignment::create([
            'dormitory_id' => $data['dormitory_id'],
            'student_id' => $data['student_id'],
            'room_no' => $data['room_no'] ?? null,
            'assigned_at' => $data['assigned_at'] ?? Carbon::now(),
            'is_current' => true,
        ]);

        return back();
    }

    public function release(DormAssignment $assignment)
    {
        $assignment->update([
            'is_current' => false,
            'released_at' => Carbon::now(),
        ]);

        return back();
    }
}
