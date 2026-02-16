<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'amount',
        'payment_category_id',
        'section_id',
        'academic_year_id',
        'class_id',
        'gender',
        'term_id',
    ];

    public function paymentCategory()
    {
        return $this->belongsTo(PaymentCategory::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

}
