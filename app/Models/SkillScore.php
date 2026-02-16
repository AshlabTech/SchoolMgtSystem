<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkillScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'skill_id',
        'exam_id',
        'class_id',
        'section_id',
        'academic_year_id',
        'rating',
        'comment',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
