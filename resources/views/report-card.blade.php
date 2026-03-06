<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Report Card</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.2;
            padding: 15px;
        }
        
        .report-card {
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #000;
            padding: 10px;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 6px;
            margin-bottom: 8px;
        }
        
        .header h1 {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 3px;
            text-transform: uppercase;
        }
        
        .header .school-info {
            font-size: 9pt;
            margin-bottom: 2px;
        }
        
        .header .term-info {
            font-size: 11pt;
            font-weight: bold;
            margin-top: 4px;
            text-decoration: underline;
        }
        
        .student-info {
            display: table;
            width: 100%;
            margin-bottom: 8px;
            border: 1px solid #000;
        }
        
        .student-info-row {
            display: table-row;
        }
        
        .student-info-cell {
            display: table-cell;
            padding: 3px 6px;
            border: 0.5pt solid #000;
            font-size: 9pt;
        }
        
        .student-info-cell.label {
            font-weight: bold;
            width: 25%;
            background-color: #f0f0f0;
        }
        
        /* Main content with side-by-side layout */
        .content-wrapper {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }
        
        .content-row {
            display: table-row;
        }
        
        .subject-results-cell {
            display: table-cell;
            vertical-align: top;
            width: 68%;
            padding-right: 5px;
        }
        
        .domains-cell {
            display: table-cell;
            vertical-align: top;
            width: 32%;
        }
        
        .marks-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
            font-size: 8pt;
        }
        
        .marks-table th {
            color: #000;
            padding: 4px 3px;
            border: 0.5pt solid #000;
            font-weight: bold;
            text-align: center;
        }
        
        .marks-table td {
            padding: 3px 2px;
            border: 0.5pt solid #000;
            text-align: center;
        }
        
        .marks-table td.subject-name {
            text-align: left;
            font-weight: bold;
        }
        
        .marks-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        /* Grade and score color coding */
        .grade-a, .grade-b, .score-excellent {
            color: #059669;
            font-weight: bold;
        }
        
        .grade-c, .grade-d, .score-good {
            color: #2563eb;
            font-weight: bold;
        }
        
        .grade-e, .grade-f, .score-poor {
            color: #dc2626;
            font-weight: bold;
        }
        
        /* Blue color for TCA and EXAM scores */
        .tca-score, .exam-score {
            color: #2563eb;
            font-weight: bold;
        }
        
        .summary-section {
            display: table;
            width: 68%;
            margin-bottom: 8px;
            border: 0.5pt solid #000;
        }
        
        .summary-row {
            display: table-row;
        }
        
        .summary-cell {
            display: table-cell;
            padding: 4px 6px;
            border: 0.5pt solid #000;
            font-size: 9pt;
        }
        
        .summary-cell.label {
            font-weight: bold;
            width: 40%;
            background-color: #f0f0f0;
        }
        
        .grading-key {
            margin-bottom: 8px;
            padding: 5px;
            border: 0.5pt solid #000;
            background-color: #f9f9f9;
        }
        
        .rating-scale {
            width: 68%;
            margin-bottom: 8px;
            padding: 3px;
            border: 0.5pt solid #000;
            background-color: #f9f9f9;
            text-align: center;
            font-size: 8pt;
        }
        
        .grading-key h3 {
            font-size: 10pt;
            margin-bottom: 3px;
            text-decoration: underline;
        }
        
        .grading-key-row {
            display: inline-block;
            margin-right: 12px;
            font-size: 8pt;
        }
        
        .skills-section {
            margin-bottom: 6px;
        }
        
        .skills-section h3 {
            font-size: 8pt;
            margin-bottom: 2px;
            padding: 2px;
            background-color: #333;
            color: white;
            text-align: center;
            font-weight: bold;
        }
        
        .skills-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
            font-size: 7pt;
        }
        
        .skills-table td {
            padding: 2px 3px;
            border: 0.5pt solid #000;
            text-align: center;
        }
        
        .skills-table td.skill-name {
            text-align: left;
            width: 70%;
            font-size: 6.5pt;
        }
        
        .skills-table td.skill-rating {
            width: 30%;
            text-align: center;
        }
        
        .comments-section {
            margin-bottom: 8px;
        }
        
        .comment-box {
            border: 0.5pt solid #000;
            padding: 5px;
            margin-bottom: 6px;
            min-height: 35px;
        }
        
        .comment-box h4 {
            font-size: 9pt;
            font-weight: bold;
            margin-bottom: 3px;
            text-decoration: underline;
        }
        
        .comment-box p {
            font-size: 8pt;
            line-height: 1.3;
        }
        
        .signatures {
            display: table;
            width: 100%;
            margin-top: 10px;
        }
        
        .signature-row {
            display: table-row;
        }
        
        .signature-cell {
            display: table-cell;
            width: 33.33%;
            padding: 8px 5px;
            text-align: center;
            font-size: 8pt;
        }
        
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 20px;
            padding-top: 3px;
            font-weight: bold;
        }
        
        .footer {
            text-align: center;
            margin-top: 8px;
            padding-top: 6px;
            border-top: 2px solid #000;
            font-size: 8pt;
            font-style: italic;
        }
        
        @media print {
            body {
                padding: 5px;
                font-size: 9pt;
            }
            .report-card {
                border: none;
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="report-card">
        <!-- Header Section -->
        <div class="header">
            <h1>{{ $schoolName ?? 'SCHOOL NAME' }}</h1>
            <div class="school-info">{{ $schoolAddress ?? 'School Address' }}</div>
            <div class="school-info">{{ $schoolContact ?? 'Tel: 000-000-0000 | Email: info@school.com' }}</div>
            <div class="term-info">TERMINAL REPORT CARD</div>
            <div class="school-info" style="margin-top: 3px;">
                <strong>{{ $academicYear ?? '2024/2025' }} Academic Session - {{ $term ?? 'First Term' }}</strong>
            </div>
        </div>

        <!-- Student Information -->
        <div class="student-info">
            <div class="student-info-row">
                <div class="student-info-cell label">Student Name:</div>
                <div class="student-info-cell">{{ $studentName ?? '' }}</div>
                <div class="student-info-cell label">Admission No:</div>
                <div class="student-info-cell">{{ $admissionNo ?? '' }}</div>
            </div>
            <div class="student-info-row">
                <div class="student-info-cell label">Class:</div>
                <div class="student-info-cell">{{ $class ?? '' }}</div>
                <div class="student-info-cell label">Section:</div>
                <div class="student-info-cell">{{ $section ?? '' }}</div>
            </div>
            <div class="student-info-row">
                <div class="student-info-cell label">Number in Class:</div>
                <div class="student-info-cell">{{ $totalStudents ?? '' }}</div>
                <div class="student-info-cell label">Date:</div>
                <div class="student-info-cell">{{ $reportDate ?? date('d/m/Y') }}</div>
            </div>
        </div>

        <!-- Grading Key -->
        <div class="grading-key">
            <h3>GRADING SYSTEM</h3>
            @if(isset($grades) && count($grades) > 0)
                @foreach($grades as $grade)
                    <div class="grading-key-row">
                        <strong>{{ $grade['name'] }}:</strong> {{ $grade['mark_from'] }}-{{ $grade['mark_to'] }} ({{ $grade['remark'] }})
                    </div>
                @endforeach
            @else
                <div class="grading-key-row"><strong>A:</strong> 70-100 (Excellent)</div>
                <div class="grading-key-row"><strong>B:</strong> 60-69 (Very Good)</div>
                <div class="grading-key-row"><strong>C:</strong> 50-59 (Good)</div>
                <div class="grading-key-row"><strong>D:</strong> 45-49 (Pass)</div>
                <div class="grading-key-row"><strong>E:</strong> 40-44 (Poor)</div>
                <div class="grading-key-row"><strong>F:</strong> 0-39 (Fail)</div>
            @endif
        </div>

        <!-- Main Content: Subject Results and Domains Side-by-Side -->
        <div class="content-wrapper">
            <div class="content-row">
                <!-- Left Side: Subject Results -->
                <div class="subject-results-cell">
                    <table class="marks-table">
                        <thead>
                            <tr>
                                <th rowspan="2">SUBJECTS</th>
                                <th colspan="{{ $caComponents ?? 4 }}">CONTINUOUS ASSESSMENT</th>
                                <th rowspan="2">TOTAL CA<br/>({{ $caWeight ?? 40 }})</th>
                                <th rowspan="2">EXAM<br/>({{ $examWeight ?? 60 }})</th>
                                <th rowspan="2">TOTAL<br/>(100)</th>
                                <th rowspan="2">GRADE</th>
                                <th rowspan="2">REMARK</th>
                                <th rowspan="2">POS</th>
                            </tr>
                            <tr>
                                @for($i = 1; $i <= ($caComponents ?? 4); $i++)
                                    <th>T{{ $i }}</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($marks) && count($marks) > 0)
                                @foreach($marks as $mark)
                                    @php
                                        $grade = strtoupper($mark['grade'] ?? '');
                                        $total = $mark['total'] ?? 0;
                                        
                                        // Determine grade color class
                                        $gradeClass = '';
                                        if (in_array($grade, ['A', 'B'])) {
                                            $gradeClass = 'grade-a';
                                        } elseif (in_array($grade, ['C', 'D'])) {
                                            $gradeClass = 'grade-c';
                                        } elseif (in_array($grade, ['E', 'F'])) {
                                            $gradeClass = 'grade-e';
                                        }
                                        
                                        // Determine score color class
                                        $scoreClass = '';
                                        if ($total >= 70) {
                                            $scoreClass = 'score-excellent';
                                        } elseif ($total >= 50) {
                                            $scoreClass = 'score-good';
                                        } elseif ($total > 0) {
                                            $scoreClass = 'score-poor';
                                        }
                                    @endphp
                                    <tr>
                                        <td class="subject-name">{{ $mark['subject'] ?? '' }}</td>
                                        <td>{{ $mark['t1'] ?? '-' }}</td>
                                        <td>{{ $mark['t2'] ?? '-' }}</td>
                                        @if(($caComponents ?? 4) >= 3)
                                            <td>{{ $mark['t3'] ?? '-' }}</td>
                                        @endif
                                        @if(($caComponents ?? 4) >= 4)
                                            <td>{{ $mark['t4'] ?? '-' }}</td>
                                        @endif
                                        <td class="tca-score">{{ $mark['tca'] ?? '-' }}</td>
                                        <td class="exam-score">{{ $mark['exam'] ?? '-' }}</td>
                                        <td class="{{ $scoreClass }}"><strong>{{ $mark['total'] ?? '-' }}</strong></td>
                                        <td class="{{ $gradeClass }}"><strong>{{ $mark['grade'] ?? '-' }}</strong></td>
                                        <td>{{ $mark['remark'] ?? '-' }}</td>
                                        <td>{{ $mark['position'] ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="{{ 6 + ($caComponents ?? 4) }}" style="text-align: center;">No marks recorded</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                
                <!-- Right Side: Psychomotor and Affective Domains -->
                <div class="domains-cell">
                    <!-- Psychomotor Skills -->
                    @if(isset($psychomotorSkills) && count($psychomotorSkills) > 0)
                    <div class="skills-section">
                        <h3>PSYCHOMOTOR (Skills)</h3>
                        <table class="skills-table">
                            <tbody>
                                @foreach($psychomotorSkills as $skill)
                                    <tr>
                                        <td class="skill-name">{{ $skill['name'] ?? '' }}</td>
                                        <td class="skill-rating">{{ $skill['rating'] ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif

                    <!-- Affective Skills -->
                    @if(isset($affectiveSkills) && count($affectiveSkills) > 0)
                    <div class="skills-section">
                        <h3>AFFECTIVE (Behavior)</h3>
                        <table class="skills-table">
                            <tbody>
                                @foreach($affectiveSkills as $skill)
                                    <tr>
                                        <td class="skill-name">{{ $skill['name'] ?? '' }}</td>
                                        <td class="skill-rating">{{ $skill['rating'] ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                    
                    <!-- Rating Scale -->
                    <div class="grading-key" style="text-align: center; font-size: 6pt; padding: 2px; margin-top: 4px;">
                        <strong>RATING:</strong> 5-Excellent | 4-V.Good | 3-Good | 2-Fair | 1-Poor
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Section -->
        <div class="summary-section">
            <div class="summary-row">
                <div class="summary-cell label">Total Marks Obtained:</div>
                <div class="summary-cell"><strong>{{ $totalMarks ?? '-' }}</strong></div>
                <div class="summary-cell label">Average:</div>
                <div class="summary-cell"><strong>{{ $average ?? '-' }}%</strong></div>
            </div>
            <div class="summary-row">
                <div class="summary-cell label">Class Average:</div>
                <div class="summary-cell"><strong>{{ $classAverage ?? '-' }}%</strong></div>
                <div class="summary-cell label">Position in Class:</div>
                <div class="summary-cell"><strong>{{ $position ?? '-' }}</strong></div>
            </div>
        </div>

        <!-- Rating Scale -->
        <div class="rating-scale">
            <strong>RATING SCALE:</strong> 5 - Excellent | 4 - Very Good | 3 - Good | 2 - Fair | 1 - Poor
        </div>

        <!-- Comments Section -->
        <div class="comments-section">
            <div class="comment-box">
                <h4>FORM TEACHER'S COMMENT:</h4>
                <p>{{ $teacherComment ?? 'No comment provided.' }}</p>
            </div>
            
            <div class="comment-box">
                <h4>PRINCIPAL'S COMMENT:</h4>
                <p>{{ $principalComment ?? '' }}</p>
            </div>
        </div>

        <!-- Signatures -->
        <div class="signatures">
            <div class="signature-row">
                <div class="signature-cell">
                    <div class="signature-line">Form Teacher</div>
                </div>
                <div class="signature-cell">
                    <div class="signature-line">Principal</div>
                </div>
                <div class="signature-cell">
                    <div class="signature-line">Parent/Guardian</div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            Next Term Begins: {{ $nextTermDate ?? '________' }}
        </div>
    </div>
</body>
</html>
