<?php

namespace App\Models\Scopes;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class VisibleTeacherScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $validUntil = now()->subMonths(Teacher::MONTHS_VALIDITY);

        $builder->where(fn ($query) => $query
            ->whereHas('authorisationCohort', fn ($q) => $q
                ->where('authorisation_date', '>', $validUntil)
            )
            ->orWhereHas('latestUpdateCohort', fn ($q) => $q
                ->where('course_date', '>', $validUntil)
            )
        );
    }
}
