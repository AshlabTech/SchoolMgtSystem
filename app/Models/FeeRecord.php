<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'fee_definition_id',
        'student_id',
        'reference',
        'amount_paid',
        'balance',
        'is_paid',
        'academic_year_id',
    ];

    protected $casts = [
        'is_paid' => 'boolean',
    ];

    public function feeDefinition()
    {
        return $this->belongsTo(FeeDefinition::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }
}
