<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $english_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Region> $regions
 * @property-read int|null $regions_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Continent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Continent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Continent query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Continent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Continent whereEnglishName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Continent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Continent whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Continent extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'english_name',
    ];

    /**
     * Get the regions in the continent.
     *
     * @return HasMany<Region, $this>
     */
    public function regions(): HasMany
    {
        return $this->hasMany(Region::class);
    }
}
