<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\ClassLevel;
use App\Models\FeeRecord;
use App\Models\InvoiceType;
use App\Models\PaymentCategory;
use App\Models\Receipt;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\StudentEnrollment;
use App\Models\Term;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentsController extends Controller
{
    public function index()
    {
        $records = FeeRecord::query()
            ->with(['invoiceType.paymentCategory', 'student.user.profile', 'student.currentEnrollment.schoolClass'])
            ->orderByDesc('id')
            ->get();

        return Inertia::render('Payments/Index', [
            'definitions' => InvoiceType::query()
                ->with(['paymentCategory', 'section', 'schoolClass', 'academicYear', 'term'])
                ->orderByDesc('id')
                ->get(),
            'records' => $records,
            'classes' => SchoolClass::query()->with('level')->orderBy('name')->get(),
            'classLevels' => ClassLevel::query()->orderBy('name')->get(),
            'sections' => Section::query()->orderBy('name')->get(),
            'years' => AcademicYear::query()->orderByDesc('name')->get(),
            'terms' => Term::query()->orderBy('order')->get(),
            'paymentCategories' => PaymentCategory::query()->orderBy('name')->get(),
            'invoiceTypes' => InvoiceType::query()
                ->with(['paymentCategory', 'section', 'schoolClass', 'academicYear', 'term'])
                ->orderBy('name')
                ->get(),
            'paymentSummary' => [
                'total_billed' => (int) ($records->sum('amount_paid') + $records->sum('balance')),
                'total_paid' => (int) $records->sum('amount_paid'),
                'total_outstanding' => (int) $records->sum('balance'),
                'paid_records' => (int) $records->where('is_paid', true)->count(),
            ],
        ]);
    }

    public function storeDefinition(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'integer', 'min:0'],
            'payment_category_id' => ['required', 'integer', 'exists:payment_categories,id'],
            'section_id' => ['nullable', 'integer', 'exists:sections,id'],
            'academic_year_id' => ['nullable', 'integer', 'exists:academic_years,id'],
            'class_id' => ['nullable', 'integer', 'exists:classes,id'],
            'gender' => ['nullable', 'string', 'max:20'],
            'term_id' => ['nullable', 'integer', 'exists:terms,id'],
        ]);

        [$data, $validationError] = $this->inferAndValidateSection($data);
        if ($validationError) {
            return $validationError;
        }

        InvoiceType::create($data);

        return back();
    }

    public function generateRecords(Request $request)
    {
        $data = $request->validate([
            'invoice_type_id' => ['required', 'integer', 'exists:invoice_types,id'],
        ]);

        $invoiceType = InvoiceType::query()->findOrFail($data['invoice_type_id']);

        $students = StudentEnrollment::query()
            ->where('is_current', true)
            ->when($invoiceType->class_id, fn ($q) => $q->where('class_id', $invoiceType->class_id))
            ->when($invoiceType->section_id, fn ($q) => $q->where('section_id', $invoiceType->section_id))
            ->when($invoiceType->academic_year_id, fn ($q) => $q->where('academic_year_id', $invoiceType->academic_year_id))
            ->when($invoiceType->term_id, fn ($q) => $q->where('term_id', $invoiceType->term_id))
            ->when($invoiceType->gender, function ($q) use ($invoiceType) {
                $q->whereHas('student.user.profile', fn ($profile) => $profile->where('gender', $invoiceType->gender));
            })
            ->get();

        foreach ($students as $enrollment) {
            $academicYearId = $invoiceType->academic_year_id ?: $enrollment->academic_year_id;
            $record = FeeRecord::firstOrCreate(
                [
                    'invoice_type_id' => $invoiceType->id,
                    'student_id' => $enrollment->student_id,
                    'academic_year_id' => $academicYearId,
                ],
                [
                    'reference' => (string) random_int(100000, 99999999),
                    'amount_paid' => 0,
                    'balance' => $invoiceType->amount,
                    'is_paid' => false,
                ]
            );

            if (!$record->reference) {
                $record->update(['reference' => (string) random_int(100000, 99999999)]);
            }
        }

        return back();
    }

    public function payNow(Request $request, FeeRecord $record)
    {
        $data = $request->validate([
            'amount' => ['required', 'integer', 'min:1', 'max:'.$record->balance],
        ]);

        $newPaid = $record->amount_paid + $data['amount'];
        $balance = max(0, ($record->invoiceType?->amount ?? 0) - $newPaid);

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
            'record' => $record->load(['invoiceType.paymentCategory', 'student.user.profile', 'receipts']),
        ]);
    }

    public function updateDefinition(Request $request, InvoiceType $definition)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'integer', 'min:0'],
            'payment_category_id' => ['required', 'integer', 'exists:payment_categories,id'],
            'section_id' => ['nullable', 'integer', 'exists:sections,id'],
            'academic_year_id' => ['nullable', 'integer', 'exists:academic_years,id'],
            'class_id' => ['nullable', 'integer', 'exists:classes,id'],
            'gender' => ['nullable', 'string', 'max:20'],
            'term_id' => ['nullable', 'integer', 'exists:terms,id'],
        ]);

        [$data, $validationError] = $this->inferAndValidateSection($data);
        if ($validationError) {
            return $validationError;
        }

        $definition->update($data);

        return back();
    }

    public function resetRecord(FeeRecord $record)
    {
        $record->update([
            'amount_paid' => 0,
            'balance' => $record->invoiceType?->amount ?? $record->balance,
            'is_paid' => false,
        ]);
        $record->receipts()->delete();

        return back();
    }

    public function storeCategory(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120', 'unique:payment_categories,name'],
        ]);

        PaymentCategory::create($data);

        return back();
    }

    public function storeInvoiceType(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'amount' => ['required', 'integer', 'min:0'],
            'payment_category_id' => ['required', 'integer', 'exists:payment_categories,id'],
            'section_id' => ['nullable', 'integer', 'exists:sections,id'],
            'academic_year_id' => ['nullable', 'integer', 'exists:academic_years,id'],
            'class_id' => ['nullable', 'integer', 'exists:classes,id'],
            'gender' => ['nullable', 'string', 'max:20'],
            'term_id' => ['nullable', 'integer', 'exists:terms,id'],
        ]);

        [$data, $validationError] = $this->inferAndValidateSection($data);
        if ($validationError) {
            return $validationError;
        }

        InvoiceType::create($data);

        return back();
    }

    private function inferAndValidateSection(array $data): array
    {
        if (!empty($data['class_id']) && empty($data['section_id'])) {
            $data['section_id'] = Section::query()->forClass($data['class_id'])->orderBy('id')->value('id');

            if (empty($data['section_id'])) {
                return [$data, back()->withErrors(['section_id' => 'No section is currently mapped to the selected class.'])];
            }
        }

        if (!empty($data['class_id']) && !empty($data['section_id'])) {
            $isValidSection = Section::query()
                ->forClass($data['class_id'])
                ->where('id', $data['section_id'])
                ->exists();

            if (!$isValidSection) {
                return [$data, back()->withErrors(['section_id' => 'Selected section does not belong to the selected class.'])];
            }
        }

        return [$data, null];
    }
}
