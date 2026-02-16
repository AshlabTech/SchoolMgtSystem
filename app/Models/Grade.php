<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_level_id',
        'name',
        'mark_from',
        'mark_to',
        'remark',
    ];

    public function classLevel()
    {
        return $this->belongsTo(ClassLevel::class);
    }
}
