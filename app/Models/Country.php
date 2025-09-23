<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $english_name
 * @property string $local_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Teacher> $teachersFrom
 * @property-read int|null $teachers_from_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Teacher> $teachersResiding
 * @property-read int|null $teachers_residing_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TuitionLocation> $tuitionLocations
 * @property-read int|null $tuition_locations_count
 * @method static \Database\Factories\CountryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereEnglishName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereLocalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Country whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
