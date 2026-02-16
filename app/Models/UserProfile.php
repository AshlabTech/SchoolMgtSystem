<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'other_name',
        'date_of_birth',
        'gender',
        'photo_path',
        'phone',
        'phone_alt',
        'address',
        'blood_group_id',
        'state_id',
        'lga_id',
        'nationality_id',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
