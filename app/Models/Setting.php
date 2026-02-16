<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'label',
        'group',
        'value',
        'type',
        'options',
        'options_source',
        'options_label',
        'options_value',
        'description',
        'is_locked',
    ];

    protected $casts = [
        'options' => 'array',
        'is_locked' => 'boolean',
    ];
}
