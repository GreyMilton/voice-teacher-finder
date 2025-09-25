<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $english_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Teacher> $teachersFrom
 * @property-read int|null $teachers_from_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Teacher> $teachersResiding
 * @property-read int|null $teachers_residing_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TuitionLocation> $tuitionLocations
 * @property-read int|null $tuition_locations_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Territory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Territory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Territory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Territory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Territory whereEnglishName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Territory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Territory whereLocalName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Territory whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Territory extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>
     */
    protected $guarded = [];

    /**
     * Get the teachers originally from the territory.
     *
     * @return HasMany<Teacher, $this>
     */
    public function teachersFrom(): HasMany
    {
        return $this->hasMany(Teacher::class, 'territory_of_origin_id');
    }

    /**
     * Get the teachers who reside in the territory.
     *
     * @return HasMany<Teacher, $this>
     */
    public function teachersResiding(): HasMany
    {
        return $this->hasMany(Teacher::class, 'territory_of_residence_id');
    }

    /**
     * Get the tuition locations in the territory.
     *
     * @return HasMany<TuitionLocation, $this>
     */
    public function tuitionLocations(): HasMany
    {
        return $this->hasMany(TuitionLocation::class);
    }
}
