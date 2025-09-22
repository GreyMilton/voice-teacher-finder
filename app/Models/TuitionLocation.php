<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TuitionLocation extends Model
{
    use HasFactory;

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['country'];

    /**
     * Get the location's title - the suburb and the country's english name combined.
     */
    protected function title(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->suburb.', '.$this->country->english_name,
        );
    }

    /**
     * Get the country this location is in.
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
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
