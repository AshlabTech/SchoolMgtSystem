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
use App\Models\Setting;
use App\Models\Student;
use App\Models\StudentEnrollment;
use App\Models\Term;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentsController extends Controller
{
    public function index()
    {
        $filters = [
            'status' => request()->string('status')->toString() ?: null,
            'class_id' => request()->integer('class_id') ?: null,
            'student_id' => request()->integer('student_id') ?: null,
            'search' => request()->string('search')->toString() ?: null,
        ];

        $records = $this->recordsQuery($filters)->get();

        return Inertia::render('Payments/Index', [
            'definitions' => InvoiceType::query()
                ->with(['paymentCategory', 'section', 'schoolClass', 'academicYear', 'term'])
                ->orderByDesc('id')
                ->get(),
            'records' => $records,
            'classes' => SchoolClass::query()->with('level')->orderBy('name')->get(),
            'students' => Student::query()
                ->with(['user.profile', 'currentEnrollment.schoolClass'])
                ->orderByDesc('id')
                ->get(),
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
                'unpaid_records' => (int) $records->where('is_paid', false)->count(),
            ],
            'filters' => $filters,
        ]);
    }

    public function export(Request $request)
    {
        $filters = [
            'status' => $request->string('status')->toString() ?: null,
            'class_id' => $request->integer('class_id') ?: null,
            'student_id' => $request->integer('student_id') ?: null,
            'search' => $request->string('search')->toString() ?: null,
        ];

        $scope = $request->string('scope')->toString() ?: 'all';
        if ($scope === 'paid') {
            $filters['status'] = 'paid';
        }
        if ($scope === 'unpaid') {
            $filters['status'] = 'unpaid';
        }

        $records = $this->recordsQuery($filters)->get();

        $csvHeaders = [
            'Student',
            'Class',
            'Invoice',
            'Category',
            'Amount',
            'Paid',
            'Balance',
            'Status',
            'Reference',
        ];

        return response()->streamDownload(function () use ($records, $csvHeaders) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $csvHeaders);

            foreach ($records as $record) {
                fputcsv($handle, [
                    $this->resolveStudentName($record->student),
                    $record->student?->currentEnrollment?->schoolClass?->name ?: '—',
                    $record->invoiceType?->name ?: '—',
                    $record->invoiceType?->paymentCategory?->name ?: '—',
                    (int) ($record->invoiceType?->amount ?? 0),
                    (int) $record->amount_paid,
                    (int) $record->balance,
                    $record->is_paid ? 'Paid' : 'Unpaid',
                    $record->reference,
                ]);
            }

            fclose($handle);
        }, 'payments-report-'.now()->format('Ymd-His').'.csv');
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

            if (! $record->reference) {
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

    public function downloadReceipt(FeeRecord $record)
    {
        $record->load(['invoiceType.paymentCategory', 'student.user.profile', 'receipts']);

        // Get school settings
        $schoolSettings = Setting::query()
            ->where('group', 'school_profile')
            ->pluck('value', 'key');

        // Prepare student name
        $studentName = trim(
            ($record->student?->user?->profile?->first_name ?? '').' '.
            ($record->student?->user?->profile?->last_name ?? '')
        ) ?: 'Unknown Student';

        // Prepare data for PDF
        $data = [
            'schoolName' => $schoolSettings['school_name'] ?? config('app.name'),
            'schoolAddress' => $schoolSettings['school_address'] ?? '',
            'schoolPhone' => $schoolSettings['school_phone'] ?? '',
            'schoolEmail' => $schoolSettings['school_email'] ?? '',
            'schoolMotto' => $schoolSettings['school_motto'] ?? '',
            'studentName' => $studentName,
            'invoiceName' => $record->invoiceType?->name ?? 'N/A',
            'categoryName' => $record->invoiceType?->paymentCategory?->name ?? 'N/A',
            'reference' => $record->reference ?? 'N/A',
            'receipts' => $record->receipts,
            'totalPaid' => $record->amount_paid,
            'currentBalance' => $record->balance,
            'isPaid' => $record->is_paid,
        ];

        // Generate PDF with thermal receipt size (80mm width)
        $pdf = Pdf::loadView('payment-receipt', $data);
        // 80mm width = ~226.77pt, auto height for thermal receipt
        $pdf->setPaper([0, 0, 226.77, 841.89], 'portrait');

        $filename = 'payment_receipt_'.($record->reference ?? $record->id).'_'.now()->format('Y_m_d').'.pdf';

        return $pdf->download($filename);
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
        if (! empty($data['class_id']) && empty($data['section_id'])) {
            $data['section_id'] = Section::query()->forClass($data['class_id'])->orderBy('id')->value('id');

            if (empty($data['section_id'])) {
                return [$data, back()->withErrors(['section_id' => 'No section is currently mapped to the selected class.'])];
            }
        }

        if (! empty($data['class_id']) && ! empty($data['section_id'])) {
            $isValidSection = Section::query()
                ->forClass($data['class_id'])
                ->where('id', $data['section_id'])
                ->exists();

            if (! $isValidSection) {
                return [$data, back()->withErrors(['section_id' => 'Selected section does not belong to the selected class.'])];
            }
        }

        return [$data, null];
    }

    private function recordsQuery(array $filters)
    {
        $search = trim((string) ($filters['search'] ?? ''));

        return FeeRecord::query()
            ->with(['invoiceType.paymentCategory', 'student.user.profile', 'student.currentEnrollment.schoolClass'])
            ->when(($filters['status'] ?? null) === 'paid', fn ($q) => $q->where('is_paid', true))
            ->when(($filters['status'] ?? null) === 'unpaid', fn ($q) => $q->where('is_paid', false))
            ->when(! empty($filters['class_id']), fn ($q) => $q->whereHas('student.currentEnrollment', fn ($enrollment) => $enrollment->where('class_id', $filters['class_id'])))
            ->when(! empty($filters['student_id']), fn ($q) => $q->where('student_id', $filters['student_id']))
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($nested) use ($search) {
                    $nested->where('reference', 'like', '%'.$search.'%')
                        ->orWhereHas('invoiceType', fn ($invoice) => $invoice->where('name', 'like', '%'.$search.'%'))
                        ->orWhereHas('student.user', fn ($user) => $user->where('name', 'like', '%'.$search.'%'));
                });
            })
            ->orderByDesc('id');
    }

    private function resolveStudentName(?Student $student): string
    {
        $name = $student?->user?->name
            ?: trim(($student?->user?->profile?->first_name ?? '').' '.($student?->user?->profile?->last_name ?? ''));

        return $name ?: 'Unknown Student';
    }
}
