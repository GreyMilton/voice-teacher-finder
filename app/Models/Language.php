<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Language extends Model
{
    use HasFactory;

    /**
     * Get the teachers who sing in this language.
     */
    public function teachersWhoSing(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'language_sung_teacher')
            ->withTimestamps();
    }

    /**
     * Get the teachers who teach in this language.
     */
    public function teachersWhoTeachIn(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'language_taught_in_teacher')
            ->withTimestamps();
    }
}
