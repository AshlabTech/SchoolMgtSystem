<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DormAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'dormitory_id',
        'student_id',
        'room_no',
        'assigned_at',
        'released_at',
        'is_current',
    ];

    protected $casts = [
        'assigned_at' => 'date',
        'released_at' => 'date',
        'is_current' => 'boolean',
    ];

    public function dormitory()
    {
        return $this->belongsTo(Dormitory::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
