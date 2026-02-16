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

    const SKILL_TYPE_PSYCHOMOTOR = 'psychomotor';
    const SKILL_TYPE_AFFECTIVE = 'affective';

    public function classLevel()
    {
        return $this->belongsTo(ClassLevel::class);
    }

    public function skillScores()
    {
        return $this->hasMany(SkillScore::class);
    }
}
