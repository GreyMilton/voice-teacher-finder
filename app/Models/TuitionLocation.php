<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TuitionLocation extends Model
{
    /**
     * Get the country this location is in.
     */
    public function country(): BelongsToMany
    {
        return $this->belongsToMany(Country::class);
    }

    /**
     * Get the teachers who teach at this location.
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class)
            ->withTimestamps();
    }
}
