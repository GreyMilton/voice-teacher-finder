<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Instrument>
 */
class InstrumentFactory extends Factory
{
    /**
     * Instruments to select from.
     *
     * @var string[]
     */
    private array $instruments = [
        'drums',
        'guitar',
        'harpsichord',
        'maracas',
        'piano',
        'saxophone',
        'trumpet',
        'tuba',
        'xylophone',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement($this->instruments),
        ];
    }
}
