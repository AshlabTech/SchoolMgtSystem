<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\ClassLevel;
use App\Models\FeeDefinition;
use App\Models\FeeRecord;
use App\Models\Receipt;
use App\Models\SchoolClass;
use App\Models\StudentEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PaymentsController extends Controller
{
    public function index()
    {
        return Inertia::render('Payments/Index', [
            'definitions' => FeeDefinition::query()->with(['schoolClass', 'academicYear'])->orderByDesc('id')->get(),
            'records' => FeeRecord::query()->with(['feeDefinition', 'student.user.profile'])->orderByDesc('id')->get(),
            'classes' => SchoolClass::query()->with('level')->orderBy('name')->get(),
            'classLevels' => ClassLevel::query()->orderBy('name')->get(),
            'years' => AcademicYear::query()->orderByDesc('name')->get(),
        ]);
    }

    public function storeDefinition(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'integer', 'min:0'],
            'class_id' => ['nullable', 'integer', 'exists:classes,id'],
            'description' => ['nullable', 'string', 'max:255'],
            'academic_year_id' => ['nullable', 'integer', 'exists:academic_years,id'],
        ]);

        FeeDefinition::create([
            ...$data,
            'reference' => Str::upper(Str::random(10)),
            'method' => 'cash',
            'created_by' => $request->user()?->id,
            'is_active' => true,
        ]);

        return back();
    }

    public function generateRecords(Request $request)
    {
        $data = $request->validate([
            'class_id' => ['required', 'integer', 'exists:classes,id'],
            'academic_year_id' => ['nullable', 'integer', 'exists:academic_years,id'],
        ]);

        $definitions = FeeDefinition::query()
            ->where(function ($query) use ($data) {
                $query->whereNull('class_id')->orWhere('class_id', $data['class_id']);
            })
            ->when($data['academic_year_id'] ?? null, fn ($q) => $q->where('academic_year_id', $data['academic_year_id']))
            ->get();

        $students = StudentEnrollment::query()
            ->where('class_id', $data['class_id'])
            ->when($data['academic_year_id'] ?? null, fn ($q) => $q->where('academic_year_id', $data['academic_year_id']))
            ->get();

        foreach ($definitions as $definition) {
            foreach ($students as $enrollment) {
                $record = FeeRecord::firstOrCreate(
                    [
                        'fee_definition_id' => $definition->id,
                        'student_id' => $enrollment->student_id,
                        'academic_year_id' => $data['academic_year_id'],
                    ],
                    [
                        'reference' => (string) random_int(100000, 99999999),
                        'amount_paid' => 0,
                        'balance' => $definition->amount,
                        'is_paid' => false,
                    ]
                );

                if (!$record->reference) {
                    $record->update(['reference' => (string) random_int(100000, 99999999)]);
                }
            }
        }

        return back();
    }

    public function payNow(Request $request, FeeRecord $record)
    {
        $data = $request->validate([
            'amount' => ['required', 'integer', 'min:1'],
        ]);

        $newPaid = $record->amount_paid + $data['amount'];
        $balance = max(0, $record->feeDefinition->amount - $newPaid);

        $record->update([
            'amount_paid' => $newPaid,
            'balance' => $balance,
            'is_paid' => $balance <= 0,
        ]);

        Receipt::create([
            'fee_record_id' => $record->id,
            'amount_paid' => $data['amount'],
            'balance' => $balance,
            'academic_year_id' => $record->academic_year_id,
            'issued_at' => now(),
        ]);

        return back();
    }

    public function receipts(FeeRecord $record)
    {
        return Inertia::render('Payments/Receipts', [
            'record' => $record->load(['feeDefinition', 'student.user.profile', 'receipts']),
        ]);
    }

    public function updateDefinition(Request $request, FeeDefinition $definition)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'integer', 'min:0'],
            'class_id' => ['nullable', 'integer', 'exists:classes,id'],
            'description' => ['nullable', 'string', 'max:255'],
            'academic_year_id' => ['nullable', 'integer', 'exists:academic_years,id'],
        ]);

        $definition->update($data);

        return back();
    }

    public function resetRecord(FeeRecord $record)
    {
        $record->update([
            'amount_paid' => 0,
            'balance' => $record->feeDefinition->amount,
            'is_paid' => false,
        ]);
        $record->receipts()->delete();

        return back();
    }
}
