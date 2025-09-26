<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Teacher> $teachers
 * @property-read int|null $teachers_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Instrument newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Instrument newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Instrument query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Instrument whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Instrument whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Instrument whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Instrument whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Instrument extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>
     */
    protected $guarded = [];

    /**
     * Get the teachers who play the instrument.
     *
     * @return BelongsToMany<Teacher, $this>
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class)->withTimestamps();
    }
}
