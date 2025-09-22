<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class AuthorisationCohort extends Model
{
    use HasFactory;

    /**
     * Get the authorisation cohort's title - the authorisation date formatted.
     */
    protected function title(): Attribute
    {
        return Attribute::make(
            get: function (null $value, array $attributes) {
                return Carbon::parse($attributes['authorisation_date'])->format('M y');
            }
        );
    }

    /**
     * Get the teachers from the authorisation cohort.
     */
    public function teachers(): HasMany
    {
        return $this->hasMany(Teacher::class);
    }
}
