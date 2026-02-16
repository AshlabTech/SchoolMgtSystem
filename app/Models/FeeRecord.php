<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_type_id',
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

    public function invoiceType()
    {
        return $this->belongsTo(InvoiceType::class);
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
