<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property int $id
 * @property int $cohort_id
 * @property int $teacher_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CohortTeacher newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CohortTeacher newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CohortTeacher query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CohortTeacher whereCohortId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CohortTeacher whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CohortTeacher whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CohortTeacher whereTeacherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CohortTeacher whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class CohortTeacher extends Pivot
{
    //
}
