<?php

namespace Database\Factories;

use App\Enums\CohortType;
use App\Models\Cohort;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cohort>
 */
class CohortFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $monthAndYear = fake()->unique()->date('M Y');

        return [
            'cohort_type' => CohortType::InitialAuthorisation,
            'completion_date' => Carbon::parse($monthAndYear)->format('Y-m-d'),
            'name' => Carbon::parse($monthAndYear)->format('M y'),
        ];
    }

    /**
     * Set the cohort as an initial authorisation.
     *
     * @return Factory<Cohort>
     */
    public function initialAuthorisation(): Factory
    {
        return $this->state(fn () => [
            'cohort_type' => CohortType::InitialAuthorisation,
        ]);
    }

    /**
     * Set the cohort as an update cohort.
     *
     * @return Factory<Cohort>
     */
    public function updateCohort(): Factory
    {
        return $this->state(fn () => [
            'cohort_type' => CohortType::Update,
        ]);
    }

    /**
     * Set the cohort's date to be after the given timestamp.
     *
     * @return Factory<Cohort>
     */
    public function after(int $timestamp): Factory
    {
        return $this->state(function () use ($timestamp) {
            $newTimestamp = random_int($timestamp, now()->timestamp);

            return [
                'completion_date' => Carbon::createFromTimestamp($newTimestamp)->format('Y-m-d'),
                'name' => Carbon::createFromTimestamp($newTimestamp)->format('M y'),
            ];

        });
    }

    /**
     * Set the cohort's date to be between the given timestamps.
     *
     * @return Factory<Cohort>
     */
    public function between(int $start, int $end): Factory
    {
        return $this->state(function () use ($start, $end) {
            $newTimestamp = random_int($start, $end);

            return [
                'completion_date' => Carbon::createFromTimestamp($newTimestamp)->format('Y-m-d'),
                'name' => Carbon::createFromTimestamp($newTimestamp)->format('M y'),
            ];

        });
    }

    /**
     * Set the cohort's date with the given timestamp.
     *
     * @return Factory<Cohort>
     */
    public function date(int $timestamp): Factory
    {
        return $this->state(fn () => [
            'completion_date' => Carbon::createFromTimestamp($timestamp)->format('Y-m-d'),
            'name' => Carbon::createFromTimestamp($timestamp)->format('M y'),
        ]);

    }
}
