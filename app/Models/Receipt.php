<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'fee_record_id',
        'amount_paid',
        'balance',
        'academic_year_id',
        'issued_at',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
    ];

    public function feeRecord()
    {
        return $this->belongsTo(FeeRecord::class);
    }
}
