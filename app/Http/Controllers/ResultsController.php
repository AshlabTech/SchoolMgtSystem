<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Mark;
use App\Models\ResultComment;
use App\Models\Section;
use App\Models\Setting;
use App\Models\Skill;
use App\Models\SkillScore;
use App\Models\Student;
use App\Models\Term;
use App\Services\DomainComputationService;
use App\Services\ResultComputationService;
use App\Services\ResultExportService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ResultsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if ($user && $user->hasRole('teacher') && !$user->hasRole('form_teacher') && !$user->hasAnyRole(['admin', 'super_admin'])) {
            abort(403, 'Teachers cannot view results without form teacher access.');
        }

        $isFormTeacher = $user && $user->hasRole('form_teacher') && !$user->hasAnyRole(['admin', 'super_admin']);
        $sectionIds = collect([]);
        if ($isFormTeacher) {
            $sectionIds = Section::where('teacher_id', $user->id)->pluck('id');
        }

        $students = Student::with('user.profile')
            ->when($isFormTeacher, fn ($q) => $q->whereHas('currentEnrollment', fn ($enroll) => $enroll->whereIn('section_id', $sectionIds)))
            ->orderByDesc('created_at')
            ->get();
        $exams = Exam::orderBy('name')->get();
        $years = AcademicYear::orderByDesc('name')->get();
        $terms = Term::orderBy('order')->get();
        $classes = \App\Models\SchoolClass::orderBy('name')->get();
        $sections = \App\Models\Section::orderBy('name')->get();
        
        $currentYear = AcademicYear::query()->where('is_current', true)->first();
        $currentTerm = Term::query()->where('is_current', true)->first();

        $marks = [];
        $selected = null;
        $examResult = null;

        if ($request->filled('student_id') && $request->filled('exam_id')) {
            $selected = [
                'student_id' => (int) $request->input('student_id'),
                'exam_id' => (int) $request->input('exam_id'),
                'academic_year_id' => $request->input('academic_year_id') ? (int) $request->input('academic_year_id') : null,
            ];

            if ($isFormTeacher && !$students->pluck('id')->contains($selected['student_id'])) {
                abort(403, 'You can only view results for your class.');
            }

            $marks = Mark::with(['subject', 'grade'])
                ->where('student_id', $selected['student_id'])
                ->where('exam_id', $selected['exam_id'])
                ->when($selected['academic_year_id'], fn ($q) => $q->where('academic_year_id', $selected['academic_year_id']))
                ->get();

            $examResult = ExamResult::query()
                ->where('student_id', $selected['student_id'])
                ->where('exam_id', $selected['exam_id'])
                ->when($selected['academic_year_id'], fn ($q) => $q->where('academic_year_id', $selected['academic_year_id']))
                ->first();

            // Get skill scores for this student
            $skillScores = SkillScore::query()
                ->with('skill')
                ->where('student_id', $selected['student_id'])
                ->where('exam_id', $selected['exam_id'])
                ->when($selected['academic_year_id'], fn ($q) => $q->where('academic_year_id', $selected['academic_year_id']))
                ->get();
        }

        $skills = Skill::query()->orderBy('skill_type')->orderBy('name')->get();

        return Inertia::render('Results/Index', [
            'students' => $students,
            'exams' => $exams,
            'years' => $years,
            'terms' => $terms,
            'classes' => $classes,
            'sections' => $sections,
            'marks' => $marks,
            'selected' => $selected,
            'examResult' => $examResult,
            'skillScores' => $skillScores ?? collect([]),
            'skills' => $skills ?? collect([]),
            'resultComments' => ResultComment::query()
                ->where('type', 'teacher')
                ->where('is_active', true)
                ->orderByDesc('is_default')
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get(),
            'autoApplyComments' => (bool) Setting::where('key', 'auto_apply_result_comment')->value('value'),
            'currentAcademicYearId' => $currentYear?->id,
            'currentTermId' => $currentTerm?->id,
        ]);
    }

    public function compute(Request $request)
    {
        $data = $request->validate([
            'exam_id' => ['required', 'integer', 'exists:exams,id'],
            'class_id' => ['required', 'integer', 'exists:classes,id'],
            'section_id' => ['nullable', 'integer', 'exists:sections,id'],
            'academic_year_id' => ['nullable', 'integer', 'exists:academic_years,id'],
        ]);

        $service = new ResultComputationService();
        $service->computeForExamClass(
            $data['exam_id'],
            $data['class_id'],
            $data['section_id'] ?? null,
            $data['academic_year_id'] ?? null,
        );

        // Also compute domain scores
        $domainService = new DomainComputationService();
        $domainService->computeForExamClass(
            $data['exam_id'],
            $data['class_id'],
            $data['section_id'] ?? null,
            $data['academic_year_id'] ?? null,
        );

        return back();
    }

    public function updateComment(Request $request, ExamResult $result)
    {
        $user = $request->user();
        if (!$user || !$user->hasAnyRole(['form_teacher', 'admin', 'super_admin'])) {
            abort(403, 'You are not allowed to update result comments.');
        }

        $data = $request->validate([
            'result_comment_id' => ['nullable', 'integer', 'exists:result_comments,id'],
        ]);

        $comment = null;
        if (!empty($data['result_comment_id'])) {
            $comment = ResultComment::query()->whereKey($data['result_comment_id'])->value('comment');
        }

        $result->update([
            'teacher_comment' => $comment,
        ]);

        return back();
    }

    public function exportIndividual(Request $request)
    {
        $data = $request->validate([
            'student_id' => ['required', 'integer', 'exists:students,id'],
            'exam_id' => ['required', 'integer', 'exists:exams,id'],
            'academic_year_id' => ['nullable', 'integer', 'exists:academic_years,id'],
            'format' => ['nullable', 'string', 'in:json,csv'],
        ]);

        $service = new ResultExportService();
        $result = $service->exportIndividualResult(
            $data['student_id'],
            $data['exam_id'],
            $data['academic_year_id'] ?? null
        );

        $format = $data['format'] ?? 'json';

        if ($format === 'csv') {
            return $this->exportAsCSV($result, 'student_result_' . $data['student_id']);
        }

        return response()->json($result);
    }

    public function exportClass(Request $request)
    {
        $data = $request->validate([
            'class_id' => ['required', 'integer', 'exists:classes,id'],
            'exam_id' => ['required', 'integer', 'exists:exams,id'],
            'section_id' => ['nullable', 'integer', 'exists:sections,id'],
            'academic_year_id' => ['nullable', 'integer', 'exists:academic_years,id'],
            'format' => ['nullable', 'string', 'in:json,csv'],
        ]);

        $service = new ResultExportService();
        $results = $service->exportClassResults(
            $data['class_id'],
            $data['exam_id'],
            $data['section_id'] ?? null,
            $data['academic_year_id'] ?? null
        );

        $format = $data['format'] ?? 'json';

        if ($format === 'csv') {
            return $this->exportClassAsCSV($results, 'class_results_' . $data['class_id']);
        }

        return response()->json($results);
    }

    private function exportAsCSV(array $data, string $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.csv"',
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');

            // Student info
            fputcsv($file, ['Student Information']);
            fputcsv($file, ['Name', $data['student']['name']]);
            fputcsv($file, ['Admission No', $data['student']['admission_no']]);
            fputcsv($file, ['Class', $data['student']['class']]);
            fputcsv($file, ['Section', $data['student']['section']]);
            fputcsv($file, []);

            // Marks
            fputcsv($file, ['Marks']);
            fputcsv($file, ['Subject', 'T1', 'T2', 'T3', 'T4', 'TCA', 'Exam', 'Total', 'Cum Avg', 'Grade', 'Remark', 'Position']);
            foreach ($data['marks'] as $mark) {
                fputcsv($file, [
                    $mark['subject'],
                    $mark['t1'],
                    $mark['t2'],
                    $mark['t3'],
                    $mark['t4'],
                    $mark['tca'],
                    $mark['exam'],
                    $mark['total'],
                    $mark['cum_ave'],
                    $mark['grade'],
                    $mark['remark'],
                    $mark['position'],
                ]);
            }

            // Overall result
            if ($data['result']) {
                fputcsv($file, []);
                fputcsv($file, ['Overall Result']);
                fputcsv($file, ['Total', $data['result']['total']]);
                fputcsv($file, ['Average', $data['result']['average']]);
                fputcsv($file, ['Class Average', $data['result']['class_average']]);
                fputcsv($file, ['Position', $data['result']['position']]);
                fputcsv($file, ['Psychomotor', $data['result']['psychomotor']]);
                fputcsv($file, ['Affective', $data['result']['affective']]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function exportClassAsCSV($results, string $filename)
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '.csv"',
        ];

        $callback = function () use ($results) {
            $file = fopen('php://output', 'w');

            // Header
            fputcsv($file, ['Student Name', 'Admission No', 'Total', 'Average', 'Position']);

            foreach ($results as $result) {
                fputcsv($file, [
                    $result['student']['name'],
                    $result['student']['admission_no'],
                    $result['result']['total'] ?? '',
                    $result['result']['average'] ?? '',
                    $result['result']['position'] ?? '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
