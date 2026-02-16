<?php

namespace App\Services;

use App\Models\ExamResult;
use App\Models\Grade;
use App\Models\Mark;
use App\Models\Setting;
use App\Models\SkillScore;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportCardService
{
    /**
     * Generate PDF report card for a student.
     */
    public function generateReportCard(int $studentId, int $examId, ?int $academicYearId): \Illuminate\Http\Response
    {
        $student = Student::with([
            'user.profile',
            'currentEnrollment.schoolClass',
            'currentEnrollment.section',
        ])->findOrFail($studentId);

        $exam = \App\Models\Exam::with(['academicYear', 'term'])->findOrFail($examId);

        // Get marks
        $marks = Mark::with(['subject', 'grade'])
            ->where('student_id', $studentId)
            ->where('exam_id', $examId)
            ->when($academicYearId, fn ($q) => $q->where('academic_year_id', $academicYearId))
            ->orderBy('subject_id')
            ->get();

        // Get exam result
        $examResult = ExamResult::query()
            ->where('student_id', $studentId)
            ->where('exam_id', $examId)
            ->when($academicYearId, fn ($q) => $q->where('academic_year_id', $academicYearId))
            ->first();

        // Get skill scores
        $skillScores = SkillScore::query()
            ->with('skill')
            ->where('student_id', $studentId)
            ->where('exam_id', $examId)
            ->when($academicYearId, fn ($q) => $q->where('academic_year_id', $academicYearId))
            ->get();

        // Get grading system
        $classLevelId = $student->currentEnrollment?->schoolClass?->class_level_id;
        $grades = Grade::query()
            ->where(function ($q) use ($classLevelId) {
                $q->whereNull('class_level_id');
                if ($classLevelId) {
                    $q->orWhere('class_level_id', $classLevelId);
                }
            })
            ->orderByDesc('mark_from')
            ->get();

        // Get CA components count - use section-specific if available
        $section = $student->currentEnrollment?->section;
        $caComponents = $section ? $section->getNumberOfCaComponents() : (int) (Setting::where('key', 'number_of_ca_components')->value('value') ?? 2);
        $caWeight = (int) (Setting::where('key', 'ca_total_weight')->value('value') ?? 40);
        $examWeight = (int) (Setting::where('key', 'exam_weight')->value('value') ?? 60);

        // Prepare marks data
        $marksData = $marks->map(function ($mark) {
            return [
                'subject' => $mark->subject?->name,
                't1' => $mark->t1,
                't2' => $mark->t2,
                't3' => $mark->t3,
                't4' => $mark->t4,
                'tca' => $mark->tca,
                'exam' => $mark->exm,
                'total' => $mark->cum,
                'grade' => $mark->grade?->name,
                'remark' => $mark->grade?->remark,
                'position' => $mark->sub_pos,
            ];
        })->toArray();

        // Prepare skills data
        $psychomotorSkills = $skillScores
            ->filter(fn ($s) => $s->skill?->skill_type === 'psychomotor')
            ->map(fn ($s) => [
                'name' => $s->skill?->name,
                'rating' => $s->rating,
            ])
            ->values()
            ->toArray();

        $affectiveSkills = $skillScores
            ->filter(fn ($s) => $s->skill?->skill_type === 'affective')
            ->map(fn ($s) => [
                'name' => $s->skill?->name,
                'rating' => $s->rating,
            ])
            ->values()
            ->toArray();

        // Get school settings
        $schoolName = Setting::where('key', 'school_name')->value('value') ?? config('app.name');
        $schoolAddress = Setting::where('key', 'school_address')->value('value') ?? '';
        $schoolContact = Setting::where('key', 'school_contact')->value('value') ?? '';

        // Prepare grades data
        $gradesData = $grades->map(function ($grade) {
            return [
                'name' => $grade->name,
                'mark_from' => $grade->mark_from,
                'mark_to' => $grade->mark_to,
                'remark' => $grade->remark,
            ];
        })->toArray();

        // Count total students in class
        $totalStudents = \App\Models\StudentEnrollment::query()
            ->where('class_id', $student->currentEnrollment?->class_id)
            ->when($student->currentEnrollment?->section_id, fn ($q) => $q->where('section_id', $student->currentEnrollment->section_id))
            ->count();

        // Prepare data for view
        $data = [
            'schoolName' => $schoolName,
            'schoolAddress' => $schoolAddress,
            'schoolContact' => $schoolContact,
            'academicYear' => $exam->academicYear?->name ?? '',
            'term' => $exam->term?->name ?? '',
            'studentName' => $student->user?->name ?? '',
            'admissionNo' => $student->admission_no ?? '',
            'class' => $student->currentEnrollment?->schoolClass?->name ?? '',
            'section' => $student->currentEnrollment?->section?->name ?? '',
            'totalStudents' => $totalStudents,
            'reportDate' => now()->format('d/m/Y'),
            'marks' => $marksData,
            'grades' => $gradesData,
            'caComponents' => $caComponents,
            'caWeight' => $caWeight,
            'examWeight' => $examWeight,
            'totalMarks' => $examResult?->total ?? 0,
            'average' => $examResult?->average ?? 0,
            'classAverage' => $examResult?->class_average ?? 0,
            'position' => $examResult?->position ?? '-',
            'psychomotorSkills' => $psychomotorSkills,
            'affectiveSkills' => $affectiveSkills,
            'teacherComment' => $examResult?->teacher_comment ?? 'Keep up the good work.',
            'principalComment' => '',
            'nextTermDate' => $exam->term?->ends_at ? $exam->term->ends_at->addDays(7)->format('d/m/Y') : '',
        ];

        // Generate PDF
        $pdf = Pdf::loadView('report-card', $data);
        $pdf->setPaper('a4', 'portrait');

        $filename = 'report_card_' . $student->admission_no . '_' . now()->format('Y_m_d') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Generate PDF report cards for all students in a class.
     */
    public function generateClassReportCards(int $classId, int $examId, ?int $sectionId, ?int $academicYearId)
    {
        $students = Student::query()
            ->with(['user', 'currentEnrollment'])
            ->whereHas('currentEnrollment', function ($q) use ($classId, $sectionId) {
                $q->where('class_id', $classId);
                if ($sectionId) {
                    $q->where('section_id', $sectionId);
                }
            })
            ->get();

        if ($students->isEmpty()) {
            // Return a proper error response instead of throwing exception
            return response()->json([
                'message' => 'No students found in the selected class. Please ensure students are enrolled in this class.',
                'error' => 'no_students_found'
            ], 404);
        }

        // For bulk download, we'll create a ZIP file with all PDFs
        // For now, return the first student's report as an example
        // In production, you'd want to create a ZIP or merge PDFs
        return $this->generateReportCard($students->first()->id, $examId, $academicYearId);
    }

    /**
     * Generate class broadsheet showing all students by subjects.
     */
    public function generateClassBroadsheet(int $classId, int $examId, ?int $sectionId, ?int $academicYearId)
    {
        $class = \App\Models\SchoolClass::findOrFail($classId);
        $exam = \App\Models\Exam::with(['academicYear', 'term'])->findOrFail($examId);
        
        $students = Student::query()
            ->with(['user', 'currentEnrollment.schoolClass', 'currentEnrollment.section'])
            ->whereHas('currentEnrollment', function ($q) use ($classId, $sectionId) {
                $q->where('class_id', $classId);
                if ($sectionId) {
                    $q->where('section_id', $sectionId);
                }
            })
            ->orderBy('id')
            ->get();

        if ($students->isEmpty()) {
            return response()->json([
                'message' => 'No students found in the selected class.',
                'error' => 'no_students_found'
            ], 404);
        }

        // Get all subjects for this class
        $subjects = \App\Models\SubjectAssignment::query()
            ->with('subject')
            ->where('class_id', $classId)
            ->when($sectionId, fn ($q) => $q->where('section_id', $sectionId))
            ->get()
            ->pluck('subject')
            ->unique('id')
            ->sortBy('name');

        // Get marks for all students
        $studentIds = $students->pluck('id');
        $marks = Mark::query()
            ->with(['subject', 'grade'])
            ->whereIn('student_id', $studentIds)
            ->where('exam_id', $examId)
            ->when($academicYearId, fn ($q) => $q->where('academic_year_id', $academicYearId))
            ->get()
            ->groupBy('student_id');

        // Get exam results for all students
        $examResults = ExamResult::query()
            ->whereIn('student_id', $studentIds)
            ->where('exam_id', $examId)
            ->when($academicYearId, fn ($q) => $q->where('academic_year_id', $academicYearId))
            ->get()
            ->keyBy('student_id');

        // Prepare broadsheet data
        $broadsheetData = $students->map(function ($student) use ($marks, $subjects, $examResults) {
            $studentMarks = $marks->get($student->id, collect());
            $studentResult = $examResults->get($student->id);
            
            $subjectScores = [];
            foreach ($subjects as $subject) {
                $mark = $studentMarks->firstWhere('subject_id', $subject->id);
                $subjectScores[$subject->code ?? $subject->name] = [
                    'total' => $mark?->cum ?? '-',
                    'grade' => $mark?->grade?->name ?? '-',
                    'position' => $mark?->sub_pos ?? '-',
                ];
            }

            return [
                'name' => $student->user?->name ?? '',
                'admission_no' => $student->admission_no ?? '',
                'subjects' => $subjectScores,
                'total' => $studentResult?->total ?? 0,
                'average' => $studentResult?->average ?? 0,
                'position' => $studentResult?->position ?? '-',
            ];
        });

        // Get school settings
        $schoolName = Setting::where('key', 'school_name')->value('value') ?? config('app.name');
        
        $data = [
            'schoolName' => $schoolName,
            'className' => $class->name,
            'sectionName' => $sectionId ? \App\Models\Section::find($sectionId)?->name : 'All Sections',
            'academicYear' => $exam->academicYear?->name ?? '',
            'term' => $exam->term?->name ?? '',
            'examName' => $exam->name,
            'subjects' => $subjects,
            'students' => $broadsheetData,
        ];

        // Generate PDF
        $pdf = Pdf::loadView('class-broadsheet', $data);
        $pdf->setPaper('a4', 'landscape');

        $filename = 'class_broadsheet_' . str_replace(' ', '_', $class->name) . '_' . now()->format('Y_m_d') . '.pdf';

        return $pdf->download($filename);
    }
}
