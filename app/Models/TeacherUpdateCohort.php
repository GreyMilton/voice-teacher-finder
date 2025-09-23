<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property int $id
 * @property int $teacher_id
 * @property int $update_cohort_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeacherUpdateCohort newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeacherUpdateCohort newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeacherUpdateCohort query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeacherUpdateCohort whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeacherUpdateCohort whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeacherUpdateCohort whereTeacherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeacherUpdateCohort whereUpdateCohortId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TeacherUpdateCohort whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class TeacherUpdateCohort extends Pivot
{
    //
}
