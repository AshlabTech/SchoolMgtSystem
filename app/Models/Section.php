<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'teacher_id',
        'name',
        'is_active',
    ];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function schoolClasses()
    {
        return $this->belongsToMany(SchoolClass::class, 'class_section', 'section_id', 'class_id');
    }

    public function scopeForClass(Builder $query, int $classId): Builder
    {
        return $query->forClasses([$classId]);
    }

    public function scopeForClasses(Builder $query, $classIds): Builder
    {
        $ids = collect($classIds)->filter()->values()->all();

        if (empty($ids)) {
            return $query->whereRaw('1 = 0');
        }

        if (!Schema::hasTable('class_section')) {
            return $query->whereIn('class_id', $ids);
        }

        return $query->where(function (Builder $nested) use ($ids) {
            $nested->whereHas('schoolClasses', function (Builder $relation) use ($ids) {
                $relation->whereIn('class_id', $ids);
            })->orWhereIn('class_id', $ids);
        });
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
