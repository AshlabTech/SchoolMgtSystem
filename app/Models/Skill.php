<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'skill_type',
        'class_level_id',
    ];

    public function classLevel()
    {
        return $this->belongsTo(ClassLevel::class);
    }
}
