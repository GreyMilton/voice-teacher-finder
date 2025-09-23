<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $english_name
 * @property string $native_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Teacher> $teachersWhoSing
 * @property-read int|null $teachers_who_sing_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Teacher> $teachersWhoTeachIn
 * @property-read int|null $teachers_who_teach_in_count
 * @method static \Database\Factories\LanguageFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Language newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Language newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Language query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Language whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Language whereEnglishName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Language whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Language whereNativeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Language whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
