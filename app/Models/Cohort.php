<?php

namespace App\Models;

use App\Enums\CohortType;
use Database\Factories\CohortFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property CohortType $cohort_type
 * @property \Illuminate\Support\Carbon $completion_date
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Teacher> $teachers
 * @property-read int|null $teachers_count
 *
 * @method static \Database\Factories\CohortFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cohort newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cohort newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cohort query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cohort whereCohortType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cohort whereCompletionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cohort whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cohort whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cohort whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cohort whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Cohort extends Model
{
    /**
     * @use HasFactory<CohortFactory>
     */
    use HasFactory;

    /**
     * The months of validity given to an authorisation or update.
     */
    const int MONTHS_VALIDITY = 36;

    /**
     * The months of warning given to admins of an expiring authorisation / update.
     */
    const int MONTHS_WARNING = 6;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['completion_date', 'cohort_type', 'name'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'completion_date' => 'date',
            'cohort_type' => CohortType::class,
        ];
    }

    /**
     * Get cohort's authorisation expiration date.
     *
     * @return Attribute<Carbon, null>
     */
    protected function authorisationExpirationDate(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes): Carbon {
                return Carbon::parse($attributes['completion_date'])
                    ->addMonths(Cohort::MONTHS_VALIDITY)
                    ->startOfDay();
            }
        );
    }

    /**
     * Get the teachers from the cohort.
     *
     * @return BelongsToMany<Teacher, $this>
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class)
            ->withTimestamps();
    }
}
