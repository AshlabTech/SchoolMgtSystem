<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimetableEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'timetable_id',
        'timeslot_id',
        'subject_id',
        'teacher_id',
        'day_of_week',
        'exam_date',
        'room',
    ];

    protected $casts = [
        'exam_date' => 'date',
    ];

    public function timetable()
    {
        return $this->belongsTo(Timetable::class);
    }

    public function timeslot()
    {
        return $this->belongsTo(Timeslot::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
