<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AuthorisationCohort extends Model
{
    use HasFactory;

    /**
     * Get the teachers from the authorisation cohort.
     */
    public function teachers(): HasMany
    {
        return $this->hasMany(Teacher::class);
    }
}
