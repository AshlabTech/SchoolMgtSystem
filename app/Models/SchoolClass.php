<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'class_level_id',
        'name',
        'code',
        'description',
        'is_active',
    ];

    public function level()
    {
        return $this->belongsTo(ClassLevel::class, 'class_level_id');
    }

    public function sections()
    {
        return $this->belongsToMany(Section::class, 'class_section', 'class_id', 'section_id');
    }

    public function legacySections()
    {
        return $this->hasMany(Section::class, 'class_id');
    }
}
