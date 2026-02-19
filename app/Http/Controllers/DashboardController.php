<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Receipt;
use App\Models\Setting;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Models\Term;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        // Get current academic year and term
        $currentYearId = Setting::query()->where('key', 'set_current_academic_year')->value('value');
        $currentTermId = Setting::query()->where('key', 'set_current_term')->value('value');

        $currentYear = $currentYearId
            ? AcademicYear::query()->find($currentYearId)
            : AcademicYear::query()->where('is_current', true)->first();

        $currentTerm = $currentTermId
            ? Term::query()->find($currentTermId)
            : Term::query()->where('is_current', true)->first();

        if (! $currentTerm && $currentYear) {
            $currentTerm = $currentYear->terms()->orderBy('order')->first();
        }

        // Student statistics
        $totalStudents = Student::query()->count();
        $activeStudents = Student::query()
            ->where('status', 'active')
            ->where('is_graduated', false)
            ->count();

        // Staff statistics
        $totalStaff = User::query()
            ->where('is_active', true)
            ->whereHas('staffProfile')
            ->count();

        $teachingStaff = User::query()
            ->where('is_active', true)
            ->whereHas('roles', function ($query) {
                $query->whereIn('name', ['teacher', 'form_teacher']);
            })
            ->count();

        // Distribution of students by class
        $studentsByClass = StudentEnrollment::query()
            ->where('is_current', true)
            ->with('schoolClass')
            ->select('class_id', DB::raw('count(*) as total'))
            ->groupBy('class_id')
            ->get()
            ->map(function ($enrollment) {
                return [
                    'class' => $enrollment->schoolClass?->name ?? 'Unknown',
                    'count' => $enrollment->total,
                ];
            })
            ->sortBy('class')
            ->values();

        // Revenue for current term
        $currentTermRevenue = 0;
        $uniqueStudentsPaidCurrentTerm = 0;

        if ($currentTerm) {
            $currentTermRevenue = Receipt::query()
                ->whereHas('feeRecord', function ($query) use ($currentTerm) {
                    $query->whereHas('invoiceType', function ($q) use ($currentTerm) {
                        $q->where('term_id', $currentTerm->id);
                    });
                })
                ->sum('amount_paid');

            $uniqueStudentsPaidCurrentTerm = Receipt::query()
                ->whereHas('feeRecord', function ($query) use ($currentTerm) {
                    $query->whereHas('invoiceType', function ($q) use ($currentTerm) {
                        $q->where('term_id', $currentTerm->id);
                    });
                })
                ->join('fee_records', 'receipts.fee_record_id', '=', 'fee_records.id')
                ->distinct('fee_records.student_id')
                ->count('fee_records.student_id');
        }

        // Revenue by time period
        $todayRevenue = Receipt::query()
            ->whereDate('issued_at', today())
            ->sum('amount_paid');

        $weekRevenue = Receipt::query()
            ->whereBetween('issued_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('amount_paid');

        $monthRevenue = Receipt::query()
            ->whereYear('issued_at', now()->year)
            ->whereMonth('issued_at', now()->month)
            ->sum('amount_paid');

        $yearRevenue = Receipt::query()
            ->whereYear('issued_at', now()->year)
            ->sum('amount_paid');

        // New enrollments
        $newEnrollmentsThisMonth = StudentEnrollment::query()
            ->whereYear('enrolled_at', now()->year)
            ->whereMonth('enrolled_at', now()->month)
            ->count();

        $newEnrollmentsThisTerm = 0;
        if ($currentTerm) {
            $newEnrollmentsThisTerm = StudentEnrollment::query()
                ->where('term_id', $currentTerm->id)
                ->count();
        }

        return Inertia::render('Dashboard', [
            'currentSession' => $currentYear?->name,
            'currentTerm' => $currentTerm?->name,
            'totalStudents' => $totalStudents,
            'activeStudents' => $activeStudents,
            'totalStaff' => $totalStaff,
            'teachingStaff' => $teachingStaff,
            'studentsByClass' => $studentsByClass,
            'currentTermRevenue' => (int) $currentTermRevenue,
            'uniqueStudentsPaidCurrentTerm' => $uniqueStudentsPaidCurrentTerm,
            'todayRevenue' => (int) $todayRevenue,
            'weekRevenue' => (int) $weekRevenue,
            'monthRevenue' => (int) $monthRevenue,
            'yearRevenue' => (int) $yearRevenue,
            'newEnrollmentsThisMonth' => $newEnrollmentsThisMonth,
            'newEnrollmentsThisTerm' => $newEnrollmentsThisTerm,
        ]);
    }
}
