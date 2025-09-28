<?php

namespace Database\Factories\Traits;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @template T of Factory
 */
trait SetsCohortTimestamps
{
    private int $afterTimestamp;

    private int $beforeTimestamp;

    /**
     * Set the cohort's date to be after the given timestamp.
     *
     * @return T
     */
    public function after(int $timestamp): Factory
    {
        $this->afterTimestamp = $timestamp;
        $end = $this->beforeTimestamp ?? now()->timestamp;

        return $this->state(function () use ($timestamp, $end) {
            $newTimestamp = random_int($timestamp, $end);

            return [
                'cohort_date' => Carbon::createFromTimestamp($newTimestamp)->format('Y-m-d'),
                'name' => Carbon::createFromTimestamp($newTimestamp)->format('M y'),
            ];

        });
    }

    /**
     * Set the cohort's date to be between the given timestamps.
     *
     * @return T
     */
    public function between(int $start, int $end): Factory
    {
        return $this->state(function () use ($start, $end) {
            $newTimestamp = random_int($start, $end);

            return [
                'cohort_date' => Carbon::createFromTimestamp($newTimestamp)->format('Y-m-d'),
                'name' => Carbon::createFromTimestamp($newTimestamp)->format('M y'),
            ];

        });
    }

    /**
     * Set the cohort's date with the given timestamp.
     *
     * @return T
     */
    public function date(int $timestamp): Factory
    {
        return $this->state(fn () => [
            'cohort_date' => Carbon::createFromTimestamp($timestamp)->format('Y-m-d'),
            'name' => Carbon::createFromTimestamp($timestamp)->format('M y'),
        ]);

    }
}
