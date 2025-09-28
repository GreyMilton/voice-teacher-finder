<?php

namespace Database\Factories;

use Database\Factories\Traits\SetsCohortTimestamps;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AuthorisationCohort>
 */
class AuthorisationCohortFactory extends Factory
{
    /** @use SetsCohortTimestamps<AuthorisationCohortFactory> */
    use SetsCohortTimestamps;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $monthAndYear = fake()->unique()->date('M Y');

        return [
            'cohort_date' => Carbon::parse($monthAndYear)->format('Y-m-d'),
            'name' => Carbon::parse($monthAndYear)->format('M y'),
        ];
    }
}
