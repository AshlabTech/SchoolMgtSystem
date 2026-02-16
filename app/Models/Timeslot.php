<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timeslot extends Model
{
    use HasFactory;

    protected $fillable = [
        'timetable_id',
        'label',
        'time_from',
        'time_to',
        'sort_order',
    ];

    public function timetable()
    {
        return $this->belongsTo(Timetable::class);
    }

    public function entries()
    {
        return $this->hasMany(TimetableEntry::class);
    }
}
