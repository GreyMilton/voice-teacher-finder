<?php

namespace App\Models;

use Database\Factories\UpdateCohortFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $course_date
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Teacher> $teachers
 * @property-read int|null $teachers_count
 *
 * @method static \Database\Factories\UpdateCohortFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UpdateCohort newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UpdateCohort newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UpdateCohort query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UpdateCohort whereCourseDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UpdateCohort whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UpdateCohort whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UpdateCohort whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UpdateCohort whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class UpdateCohort extends Model
{
    /**
     * @use HasFactory<UpdateCohortFactory>
     */
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>
     */
    protected $guarded = [];

    /**
     * Get the teachers from the update cohort.
     *
     * @return BelongsToMany<Teacher, $this>
     */
    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class)
            ->withTimestamps();
    }
}
