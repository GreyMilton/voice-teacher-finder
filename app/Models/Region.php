<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'english_name',
        'continent_id',
    ];

    /**
     * Get the continent of the region.
     *
     * @return BelongsTo<Continent, $this>
     */
    public function continent(): BelongsTo
    {
        return $this->belongsTo(Continent::class);
    }

    /**
     * Get the territories in the region.
     *
     * @return HasMany<Territory, $this>
     */
    public function territories(): HasMany
    {
        return $this->hasMany(Territory::class);
    }
}
