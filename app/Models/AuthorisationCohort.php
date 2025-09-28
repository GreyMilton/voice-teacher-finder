<?php

namespace App\Models;

use Database\Factories\AuthorisationCohortFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $cohort_date
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Teacher> $teachers
 * @property-read int|null $teachers_count
 *
 * @method static \Database\Factories\AuthorisationCohortFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthorisationCohort newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthorisationCohort newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthorisationCohort query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthorisationCohort whereCohortDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthorisationCohort whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthorisationCohort whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthorisationCohort whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AuthorisationCohort whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class AuthorisationCohort extends Model
{
    /**
     * @use HasFactory<AuthorisationCohortFactory>
     */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = ['cohort_date', 'name'];

    /**
     * Get the teachers from the authorisation cohort.
     *
     * @return HasMany<Teacher, $this>
     */
    public function teachers(): HasMany
    {
        return $this->hasMany(Teacher::class);
    }
}
