<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'admission_no',
        'joined_at',
        'is_graduated',
        'graduated_at',
        'status',
    ];

    protected $casts = [
        'joined_at' => 'date',
        'graduated_at' => 'date',
        'is_graduated' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function enrollments()
    {
        return $this->hasMany(StudentEnrollment::class);
    }

    public function currentEnrollment()
    {
        return $this->hasOne(StudentEnrollment::class)->where('is_current', true);
    }

    public function promotions()
    {
        return $this->hasMany(Promotion::class);
    }

    public function dormAssignments()
    {
        return $this->hasMany(DormAssignment::class);
    }

    public function currentDormAssignment()
    {
        return $this->hasOne(DormAssignment::class)->where('is_current', true);
    }

    public function guardians()
    {
        return $this->hasMany(StudentGuardian::class);
    }
}
