<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Teacher extends Model
{
    use HasFactory;

    /**
     * Get the teacher's authorisation cohort.
     */
    public function authorisationCohort(): BelongsTo
    {
        return $this->belongsTo(AuthorisationCohort::class);
    }

    /**
     * Get the teacher's country of origin.
     */
    public function countryOfOrigin(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_of_origin_id');
    }

    /**
     * Get the teacher's country of residence.
     */
    public function countryOfResidence(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_of_residence_id');
    }

    /**
     * Get the instruments the teacher plays.
     */
    public function instruments(): BelongsToMany
    {
        return $this->belongsToMany(Instrument::class)->withTimestamps();
    }

    /**
     * Get the languages the teacher sings in.
     */
    public function languagesSung(): BelongsToMany
    {
        return $this->belongsToMany(Language::class, 'language_sung_teacher')
            ->withTimestamps();
    }

    /**
     * Get the languages the teacher gives lessons in.
     */
    public function languagesTeachesIn(): BelongsToMany
    {
        return $this->belongsToMany(Language::class, 'language_taught_in_teacher')
            ->withTimestamps();
    }

    /**
     * Get the teacher's tuition locations (where they teach).
     */
    public function tuitionLocations(): BelongsToMany
    {
        return $this->belongsToMany(TuitionLocation::class)->withTimestamps();
    }

    /**
     * Get the teacher's update cohorts.
     */
    public function updateCohorts(): BelongsToMany
    {
        return $this->belongsToMany(UpdateCohort::class)->withTimestamps();
    }

    /**
     * Get the teacher's user account.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
