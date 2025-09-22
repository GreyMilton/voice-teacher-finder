<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

class UpdateCohort extends Model
{
    use HasFactory;

    /**
     * Get the update cohort's title - the course date formatted.
     */
    protected function title(): Attribute
    {
        return Attribute::make(
            get: function (null $value, array $attributes) {
                return Carbon::parse($attributes['course_date'])->format('M y');
            }
        );
    }

    /**
     * Get the teachers from the update cohort.
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class)
            ->withTimestamps();
    }
}
