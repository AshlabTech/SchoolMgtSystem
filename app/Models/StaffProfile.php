<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_code',
        'employment_date',
        'designation',
    ];

    protected $casts = [
        'employment_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
