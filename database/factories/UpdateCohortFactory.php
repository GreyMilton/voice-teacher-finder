<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UpdateCohort>
 */
class UpdateCohortFactory extends Factory
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
            'course_date' => Carbon::parse($monthAndYear)->format('Y-m-d'),
            'name' => Carbon::parse($monthAndYear)->format('M y'),
        ];
    }
}
