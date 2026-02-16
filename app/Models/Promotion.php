<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'from_enrollment_id',
        'to_enrollment_id',
        'from_class_id',
        'from_section_id',
        'to_class_id',
        'to_section_id',
        'from_academic_year_id',
        'to_academic_year_id',
        'is_graduated',
        'status',
    ];

    protected $casts = [
        'is_graduated' => 'boolean',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function fromEnrollment()
    {
        return $this->belongsTo(StudentEnrollment::class, 'from_enrollment_id');
    }

    public function toEnrollment()
    {
        return $this->belongsTo(StudentEnrollment::class, 'to_enrollment_id');
    }

    public function fromClass()
    {
        return $this->belongsTo(SchoolClass::class, 'from_class_id');
    }

    public function toClass()
    {
        return $this->belongsTo(SchoolClass::class, 'to_class_id');
    }

    public function fromSection()
    {
        return $this->belongsTo(Section::class, 'from_section_id');
    }

    public function toSection()
    {
        return $this->belongsTo(Section::class, 'to_section_id');
    }
}
