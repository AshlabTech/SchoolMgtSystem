<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LibraryBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'title',
        'author',
        'category',
        'location',
        'description',
        'url',
        'total_copies',
        'issued_copies',
    ];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function loans()
    {
        return $this->hasMany(LibraryLoan::class, 'book_id');
    }

    public function getAvailableCopiesAttribute(): ?int
    {
        if ($this->total_copies === null) {
            return null;
        }

        return max(0, $this->total_copies - $this->issued_copies);
    }
}
