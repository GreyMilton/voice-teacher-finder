<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Staudenmeir\EloquentHasManyDeep\HasManyDeep;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

/**
 * @property int $id
 * @property string $geo_point
 * @property string $iso_3_country_code
 * @property string $english_name
 * @property int $region_id
 * @property string $french_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Region $region
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Teacher> $teachersFrom
 * @property-read int|null $teachers_from_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Teacher> $teachersResiding
 * @property-read int|null $teachers_residing_count
 * @property-read mixed $title
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TuitionLocation> $tuitionLocations
 * @property-read int|null $tuition_locations_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Teacher[] $teachersWhoTeachIn
 * @property-read int|null $teachers_who_teach_in_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Territory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Territory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Territory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Territory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Territory whereEnglishName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Territory whereFrenchName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Territory whereGeoPoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Territory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Territory whereIso3CountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Territory whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Territory whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Territory extends Model
{
    use HasRelationships;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'geo_point',
        'iso_3_country_code',
        'english_name',
        'region_id',
        'french_name',
    ];

    /**
     * Get the territory's title, which is a concatenation of:
     * - Territory 'english_name'
     * - Region 'english_name'
     * - Continent 'english name'.
     *
     * @return Attribute<string, null>
     */
    protected function title(): Attribute
    {
        return Attribute::make(
            get: function () {
                $name = $this->english_name;
                $region = $this->region->english_name;
                $continent = $this->region->continent->english_name;

                return $name.', '.$region.', '.$continent;
            }
        );
    }

    /**
     * Get the region this territory is in.
     *
     * @return BelongsTo<Region, $this>
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

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
     * Get the teachers who teach in the territory.
     *
     * @return HasManyDeep<Teacher, $this>
     */
    public function teachersWhoTeachIn(): HasManyDeep
    {
        return $this->hasManyDeep(
            Teacher::class,
            [TuitionLocation::class, 'teacher_tuition_location'],
        );
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
