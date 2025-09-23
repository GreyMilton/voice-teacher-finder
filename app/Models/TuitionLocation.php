<?php

namespace App\Models;

use Database\Factories\TuitionLocationFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $suburb
 * @property int $country_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Country $country
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Teacher> $teachers
 * @property-read int|null $teachers_count
 * @property-read mixed $title
 *
 * @method static \Database\Factories\TuitionLocationFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TuitionLocation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TuitionLocation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TuitionLocation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TuitionLocation whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TuitionLocation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TuitionLocation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TuitionLocation whereSuburb($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TuitionLocation whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class TuitionLocation extends Model
{
    /**
     * @use HasFactory<TuitionLocationFactory>
     */
    use HasFactory;

    /**
     * The relationships that should always be loaded.
     *
     * @var list<string>
     */
    protected $with = ['country'];

    /**
     * Get the location's title - the suburb and the country's english name combined.
     *
     * @return Attribute<string, null>
     */
    protected function title(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->suburb.', '.$this->country->english_name,
        );
    }

    /**
     * Get the country this location is in.
     *
     * @return BelongsTo<Country, $this>
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get the teachers who teach at this location.
     *
     * @return BelongsToMany<Teacher, $this>
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class)
            ->withTimestamps();
    }
}
