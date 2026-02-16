<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'class_id',
        'section_id',
        'academic_year_id',
        'term_id',
        'exam_id',
        'type',
    ];

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

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function timeslots()
    {
        return $this->hasMany(Timeslot::class);
    }

    public function entries()
    {
        return $this->hasMany(TimetableEntry::class);
    }
}
