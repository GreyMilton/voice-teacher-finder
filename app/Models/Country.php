<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    use HasFactory;

    /**
     * Get the teachers originally from the country.
     */
    public function teachersFrom(): HasMany
    {
        return $this->hasMany(Teacher::class, 'country_of_origin_id');
    }

    /**
     * Get the teachers who reside in the country.
     */
    public function teachersResiding(): HasMany
    {
        return $this->hasMany(Teacher::class, 'country_of_residence_id');
    }

    /**
     * Get the tuition locations in the country.
     */
    public function tuitionLocations(): HasMany
    {
        return $this->hasMany(TuitionLocation::class);
    }
}
