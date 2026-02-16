<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Broadsheet</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 9pt;
            line-height: 1.2;
            padding: 10px;
        }
        
        .broadsheet {
            width: 100%;
            margin: 0 auto;
            border: 1pt solid #000;
            padding: 8px;
        }
        
        .header {
            text-align: center;
            border-bottom: 1pt solid #000;
            padding-bottom: 5px;
            margin-bottom: 6px;
        }
        
        .header h1 {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 2px;
            text-transform: uppercase;
        }
        
        .header .info {
            font-size: 9pt;
            margin-bottom: 2px;
        }
        
        .broadsheet-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
            font-size: 7pt;
        }
        
        .broadsheet-table th {
            background-color: #333;
            color: white;
            padding: 3px 2px;
            border: 0.5pt solid #000;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }
        
        .broadsheet-table td {
            padding: 2px;
            border: 0.5pt solid #000;
            text-align: center;
        }
        
        .broadsheet-table td.student-name {
            text-align: left;
            font-weight: bold;
            font-size: 7pt;
        }
        
        .broadsheet-table td.admission-no {
            text-align: center;
            font-size: 7pt;
        }
        
        .broadsheet-table tr:nth-child(even) {
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
        
        .footer {
            margin-top: 10px;
            text-align: right;
            font-size: 8pt;
            font-style: italic;
        }
        
        @media print {
            body {
                padding: 5px;
                font-size: 8pt;
            }
            .broadsheet {
                border: none;
                padding: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="broadsheet">
        <!-- Header Section -->
        <div class="header">
            <h1>{{ $schoolName ?? 'SCHOOL NAME' }}</h1>
            <div class="info"><strong>CLASS BROADSHEET - {{ $className ?? '' }}{{ $sectionName && $sectionName !== 'All Sections' ? ' (' . $sectionName . ')' : '' }}</strong></div>
            <div class="info">{{ $academicYear ?? '' }} - {{ $term ?? '' }} - {{ $examName ?? '' }}</div>
        </div>

        <!-- Broadsheet Table -->
        <table class="broadsheet-table">
            <thead>
                <tr>
                    <th rowspan="2">S/N</th>
                    <th rowspan="2">ADMISSION NO</th>
                    <th rowspan="2">STUDENT NAME</th>
                    @foreach($subjects as $subject)
                        <th colspan="2">{{ $subject->code ?? substr($subject->name, 0, 6) }}</th>
                    @endforeach
                    <th rowspan="2">TOTAL</th>
                    <th rowspan="2">AVG %</th>
                    <th rowspan="2">POS</th>
                </tr>
                <tr>
                    @foreach($subjects as $subject)
                        <th>Score</th>
                        <th>Pos</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @if(isset($students) && count($students) > 0)
                    @foreach($students as $index => $student)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="admission-no">{{ $student['admission_no'] ?? '-' }}</td>
                            <td class="student-name">{{ $student['name'] ?? '' }}</td>
                            @foreach($subjects as $subject)
                                @php
                                    $subjectCode = $subject->code ?? $subject->name;
                                    $subjectScore = $student['subjects'][$subjectCode] ?? ['total' => '-', 'grade' => '-', 'position' => '-'];
                                    $total = is_numeric($subjectScore['total']) ? $subjectScore['total'] : 0;
                                    $grade = strtoupper($subjectScore['grade'] ?? '');
                                    
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
                                <td class="{{ $scoreClass }}">{{ $subjectScore['total'] }}</td>
                                <td>{{ $subjectScore['position'] }}</td>
                            @endforeach
                            <td><strong>{{ $student['total'] ?? '-' }}</strong></td>
                            <td><strong>{{ is_numeric($student['average'] ?? null) ? number_format($student['average'], 1) : '-' }}</strong></td>
                            <td><strong>{{ $student['position'] ?? '-' }}</strong></td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="{{ 3 + (count($subjects) * 2) + 3 }}" style="text-align: center; padding: 10px;">
                            No students found in this class
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>

        <!-- Footer -->
        <div class="footer">
            Generated on {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>
</body>
</html>
