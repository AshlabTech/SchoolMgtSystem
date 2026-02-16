<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\AcademicsController;
use App\Http\Controllers\ClassLevelController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\StudentsController;
use App\Http\Controllers\SubjectsController;
use App\Http\Controllers\ExamsController;
use App\Http\Controllers\GradesController;
use App\Http\Controllers\MarksController;
use App\Http\Controllers\PinsController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\TimetablesController;
use App\Http\Controllers\PromotionsController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\DormsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ResultCommentsController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\MyAccountController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\ResultsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentPortalController;
use App\Http\Controllers\ParentPortalController;
use App\Http\Controllers\StaffPortalController;
use App\Http\Controllers\AcademicYearsController;
use App\Http\Controllers\TermsController;
use App\Http\Controllers\SkillsController;
use App\Http\Controllers\SkillScoresController;
use App\Models\Exam;
use App\Models\FeeRecord;
use App\Models\Receipt;
use App\Models\Student;
use App\Models\User;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    $dashboard = function () {
        return Inertia::render('Dashboard', [
            'stats' => [
                ['label' => 'Active Students', 'value' => Student::query()->where('status', 'active')->where('is_graduated', false)->count()],
                ['label' => 'Outstanding Fees', 'value' => (int) FeeRecord::query()->sum('balance'), 'is_currency' => true],
                ['label' => 'Published Exams', 'value' => Exam::query()->where('is_published', true)->count()],
                ['label' => 'Active Staff', 'value' => User::query()->where('is_active', true)->whereHas('staffProfile')->count()],
            ],
            'payments' => Receipt::query()
                ->with(['feeRecord.student.user', 'feeRecord.student.currentEnrollment.schoolClass', 'feeRecord.invoiceType'])
                ->orderByDesc('issued_at')
                ->take(5)
                ->get()
                ->map(fn (Receipt $receipt) => [
                    'student' => $receipt->feeRecord?->student?->user?->name ?? 'Unknown',
                    'class' => $receipt->feeRecord?->student?->currentEnrollment?->schoolClass?->name ?? '—',
                    'amount' => (int) $receipt->amount_paid,
                    'status' => ($receipt->feeRecord?->is_paid ?? false) ? 'Paid' : 'Pending',
                    'date' => optional($receipt->issued_at)->toDateString(),
                ])
                ->values(),
            'events' => Exam::query()
                ->with(['term', 'academicYear'])
                ->whereNotNull('starts_at')
                ->whereDate('starts_at', '>=', today())
                ->orderBy('starts_at')
                ->take(5)
                ->get()
                ->map(fn (Exam $exam) => [
                    'title' => $exam->name,
                    'date' => optional($exam->starts_at)->toDateString(),
                    'owner' => $exam->term?->name ?? 'Academic Office',
                ])
                ->values(),
        ]);
    };

    Route::get('/', $dashboard)->name('home');
    Route::get('/dashboard', $dashboard)->name('dashboard');

    Route::middleware('permission:manage.classes|manage.sections')->group(function () {
        Route::get('/academics', [AcademicsController::class, 'index'])->name('academics.index');
        Route::post('/academics/class-levels', [ClassLevelController::class, 'store'])->name('academics.class-levels.store');
        Route::put('/academics/class-levels/{classLevel}', [ClassLevelController::class, 'update'])->name('academics.class-levels.update');
        Route::delete('/academics/class-levels/{classLevel}', [ClassLevelController::class, 'destroy'])->name('academics.class-levels.destroy');
        Route::post('/academics/classes', [SchoolClassController::class, 'store'])->name('academics.classes.store');
        Route::put('/academics/classes/{schoolClass}', [SchoolClassController::class, 'update'])->name('academics.classes.update');
        Route::delete('/academics/classes/{schoolClass}', [SchoolClassController::class, 'destroy'])->name('academics.classes.destroy');
        Route::post('/academics/sections', [SectionController::class, 'store'])->name('academics.sections.store');
        Route::put('/academics/sections/{section}', [SectionController::class, 'update'])->name('academics.sections.update');
        Route::delete('/academics/sections/{section}', [SectionController::class, 'destroy'])->name('academics.sections.destroy');
    });

    Route::middleware('permission:manage.students|view.students')->group(function () {
        Route::get('/students', [StudentsController::class, 'index'])->name('students.index');
        Route::post('/students', [StudentsController::class, 'store'])->middleware('permission:manage.students')->name('students.store');
        Route::put('/students/{student}', [StudentsController::class, 'update'])->middleware('permission:manage.students')->name('students.update');
        Route::delete('/students/{student}', [StudentsController::class, 'destroy'])->middleware('permission:manage.students')->name('students.destroy');
    });

    Route::middleware('permission:manage.subjects')->group(function () {
        Route::get('/subjects', [SubjectsController::class, 'index'])->name('subjects.index');
        Route::post('/subjects', [SubjectsController::class, 'store'])->name('subjects.store');
        Route::put('/subjects/{subject}', [SubjectsController::class, 'update'])->name('subjects.update');
        Route::delete('/subjects/{subject}', [SubjectsController::class, 'destroy'])->name('subjects.destroy');
        Route::post('/subjects/assignments', [SubjectsController::class, 'assign'])->name('subjects.assign');
        Route::put('/subjects/assignments/{assignment}', [SubjectsController::class, 'updateAssignment'])->name('subjects.assignments.update');
        Route::delete('/subjects/assignments/{assignment}', [SubjectsController::class, 'destroyAssignment'])->name('subjects.assignments.destroy');
    });

    Route::middleware('permission:manage.exams')->group(function () {
        Route::get('/exams', [ExamsController::class, 'index'])->name('exams.index');
        Route::post('/exams', [ExamsController::class, 'store'])->name('exams.store');
        Route::put('/exams/{exam}', [ExamsController::class, 'update'])->name('exams.update');
        Route::delete('/exams/{exam}', [ExamsController::class, 'destroy'])->name('exams.destroy');
    });

    Route::middleware('permission:manage.grades')->group(function () {
        Route::get('/grades', [GradesController::class, 'index'])->name('grades.index');
        Route::post('/grades', [GradesController::class, 'store'])->name('grades.store');
        Route::put('/grades/{grade}', [GradesController::class, 'update'])->name('grades.update');
        Route::delete('/grades/{grade}', [GradesController::class, 'destroy'])->name('grades.destroy');
    });

    Route::middleware('permission:manage.grades')->group(function () {
        Route::get('/skills', [SkillsController::class, 'index'])->name('skills.index');
        Route::post('/skills', [SkillsController::class, 'store'])->name('skills.store');
        Route::put('/skills/{skill}', [SkillsController::class, 'update'])->name('skills.update');
        Route::delete('/skills/{skill}', [SkillsController::class, 'destroy'])->name('skills.destroy');
    });

    Route::middleware('permission:manage.marks|view.results')->group(function () {
        Route::get('/skill-scores', [SkillScoresController::class, 'index'])->name('skill-scores.index');
        Route::post('/skill-scores', [SkillScoresController::class, 'store'])->middleware('permission:manage.marks')->name('skill-scores.store');
        Route::post('/skill-scores/bulk', [SkillScoresController::class, 'bulkStore'])->middleware('permission:manage.marks')->name('skill-scores.bulk');
    });

    Route::middleware('permission:manage.marks')->group(function () {
        Route::get('/marks', [MarksController::class, 'index'])->name('marks.index');
        Route::post('/marks', [MarksController::class, 'store'])->name('marks.store');
        Route::post('/marks/students', [MarksController::class, 'listStudents'])->name('marks.students');
    });

    Route::middleware('permission:manage.marks|view.results')->group(function () {
        Route::get('/results', [ResultsController::class, 'index'])->name('results.index');
        Route::post('/results/compute', [ResultsController::class, 'compute'])->middleware('permission:manage.marks')->name('results.compute');
        Route::put('/results/{result}/comment', [ResultsController::class, 'updateComment'])->name('results.comment.update');
    });

    Route::middleware('permission:manage.pins')->group(function () {
        Route::get('/pins', [PinsController::class, 'index'])->name('pins.index');
        Route::post('/pins', [PinsController::class, 'store'])->name('pins.store');
        Route::delete('/pins/{pin}', [PinsController::class, 'destroy'])->name('pins.destroy');
    });

    Route::middleware('permission:manage.payments')->group(function () {
        Route::get('/payments', [PaymentsController::class, 'index'])->name('payments.index');
        Route::post('/payments/definitions', [PaymentsController::class, 'storeDefinition'])->name('payments.definitions.store');
        Route::put('/payments/definitions/{definition}', [PaymentsController::class, 'updateDefinition'])->name('payments.definitions.update');
        Route::post('/payments/categories', [PaymentsController::class, 'storeCategory'])->name('payments.categories.store');
        Route::post('/payments/invoice-types', [PaymentsController::class, 'storeInvoiceType'])->name('payments.invoice-types.store');
        Route::post('/payments/records/generate', [PaymentsController::class, 'generateRecords'])->name('payments.records.generate');
        Route::post('/payments/records/{record}/pay', [PaymentsController::class, 'payNow'])->name('payments.records.pay');
        Route::post('/payments/records/{record}/reset', [PaymentsController::class, 'resetRecord'])->name('payments.records.reset');
        Route::get('/payments/records/{record}/receipts', [PaymentsController::class, 'receipts'])->name('payments.records.receipts');
        Route::get('/payments/export', [PaymentsController::class, 'export'])->name('payments.export');
    });

    Route::middleware('permission:manage.timetables')->group(function () {
        Route::get('/timetables', [TimetablesController::class, 'index'])->name('timetables.index');
        Route::post('/timetables', [TimetablesController::class, 'store'])->name('timetables.store');
        Route::put('/timetables/{timetable}', [TimetablesController::class, 'update'])->name('timetables.update');
        Route::get('/timetables/{timetable}', [TimetablesController::class, 'show'])->name('timetables.show');
        Route::post('/timetables/{timetable}/timeslots', [TimetablesController::class, 'storeTimeslot'])->name('timetables.timeslots.store');
        Route::put('/timetables/timeslots/{timeslot}', [TimetablesController::class, 'updateTimeslot'])->name('timetables.timeslots.update');
        Route::delete('/timetables/timeslots/{timeslot}', [TimetablesController::class, 'destroyTimeslot'])->name('timetables.timeslots.destroy');
        Route::post('/timetables/{timetable}/entries', [TimetablesController::class, 'storeEntry'])->name('timetables.entries.store');
        Route::put('/timetables/entries/{entry}', [TimetablesController::class, 'updateEntry'])->name('timetables.entries.update');
        Route::delete('/timetables/entries/{entry}', [TimetablesController::class, 'destroyEntry'])->name('timetables.entries.destroy');
    });

    Route::middleware('permission:manage.promotions')->group(function () {
        Route::get('/promotions', [PromotionsController::class, 'index'])->name('promotions.index');
        Route::post('/promotions', [PromotionsController::class, 'store'])->name('promotions.store');
        Route::post('/promotions/{promotion}/reset', [PromotionsController::class, 'reset'])->name('promotions.reset');
    });

    Route::middleware('permission:manage.library')->group(function () {
        Route::get('/library', [LibraryController::class, 'index'])->name('library.index');
        Route::post('/library/books', [LibraryController::class, 'storeBook'])->name('library.books.store');
        Route::put('/library/books/{book}', [LibraryController::class, 'updateBook'])->name('library.books.update');
        Route::delete('/library/books/{book}', [LibraryController::class, 'destroyBook'])->name('library.books.destroy');
        Route::post('/library/loans', [LibraryController::class, 'storeLoan'])->name('library.loans.store');
        Route::post('/library/loans/{loan}/return', [LibraryController::class, 'returnLoan'])->name('library.loans.return');
    });

    Route::middleware('permission:manage.dorms')->group(function () {
        Route::get('/dorms', [DormsController::class, 'index'])->name('dorms.index');
        Route::post('/dorms', [DormsController::class, 'store'])->name('dorms.store');
        Route::put('/dorms/{dormitory}', [DormsController::class, 'update'])->name('dorms.update');
        Route::delete('/dorms/{dormitory}', [DormsController::class, 'destroy'])->name('dorms.destroy');
        Route::post('/dorms/assignments', [DormsController::class, 'assign'])->name('dorms.assign');
        Route::post('/dorms/assignments/{assignment}/release', [DormsController::class, 'release'])->name('dorms.assignments.release');
    });

    Route::middleware('permission:manage.settings')->group(function () {
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SettingsController::class, 'store'])->name('settings.store');
        Route::put('/settings/{setting}', [SettingsController::class, 'update'])->name('settings.update');
        Route::get('/comments', [ResultCommentsController::class, 'index'])->name('comments.index');
        Route::post('/comments', [ResultCommentsController::class, 'store'])->name('comments.store');
        Route::put('/comments/{comment}', [ResultCommentsController::class, 'update'])->name('comments.update');
        Route::post('/academic-years', [AcademicYearsController::class, 'store'])->name('academic-years.store');
        Route::post('/terms', [TermsController::class, 'store'])->name('terms.store');
    });

    Route::middleware('permission:manage.users')->group(function () {
        Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
        Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
        Route::put('/staff/{user}', [StaffController::class, 'update'])->name('staff.update');
        Route::post('/staff/{user}/reset-password', [StaffController::class, 'resetPassword'])->name('staff.reset-password');
        Route::delete('/staff/{user}', [StaffController::class, 'destroy'])->name('staff.destroy');
    });

    Route::get('/my-account', [MyAccountController::class, 'edit'])->name('my-account.edit');
    Route::put('/my-account', [MyAccountController::class, 'update'])->name('my-account.update');
    Route::put('/my-account/password', [MyAccountController::class, 'changePassword'])->name('my-account.password');

    Route::middleware('role:parent')->group(function () {
        Route::get('/my-children', [ParentController::class, 'index'])->name('parent.children');
    });

    Route::middleware('role:student|admin|super_admin')->group(function () {
        Route::get('/portal/student', [StudentPortalController::class, 'index'])->name('portal.student');
    });

    Route::middleware('role:parent|admin|super_admin')->group(function () {
        Route::get('/portal/parent', [ParentPortalController::class, 'index'])->name('portal.parent');
    });

    Route::middleware('role:teacher|form_teacher|admin|accountant|librarian|super_admin')->group(function () {
        Route::get('/portal/staff', [StaffPortalController::class, 'index'])->name('portal.staff');
    });

    Route::get('/ajax/lgas/{state}', [AjaxController::class, 'lgas'])->name('ajax.lgas');
    Route::get('/ajax/class-sections/{class}', [AjaxController::class, 'classSections'])->name('ajax.classSections');
    Route::get('/ajax/class-subjects/{class}', [AjaxController::class, 'classSubjects'])->name('ajax.classSubjects');
});
