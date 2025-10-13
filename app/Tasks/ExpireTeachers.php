<?php

namespace App\Tasks;

use App\Enums\AuthorisationStatus as EnumsAuthorisationStatus;
use App\Models\AuthorisationStatus;
use App\Models\Cohort;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Builder;

class ExpireTeachers
{
    public function __invoke(): void
    {
        // Fetch all authorised teachers that are due for expiry today.
        $teachers = Teacher::authorised()
            ->whereHas('latestCohort', fn (Builder $query) => $query
                ->where(
                    'completion_date',
                    now()->subMonths(Cohort::MONTHS_VALIDITY)->startOfDay(),
                )
            )
            ->get();

        // Expire all fetched teachers.
        $teachers->each(function (Teacher $teacher) {
            AuthorisationStatus::create([
                'teacher_id' => $teacher->id,
                'value' => EnumsAuthorisationStatus::Expired,
            ]);
        });
    }
}
