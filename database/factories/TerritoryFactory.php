<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Territory>
 */
class TerritoryFactory extends Factory
{
    /**
     * Territories to select from.
     *
     * @var array<array<string, string>>
     */
    private array $territories = [
        ['english_name' => 'Australia', 'local_name' => 'Australia'],
        ['english_name' => 'Belgium', 'local_name' => 'Belgique / Belgie'],
        ['english_name' => 'Denmark', 'local_name' => 'Danmark'],
        ['english_name' => 'England', 'local_name' => 'England'],
        ['english_name' => 'France', 'local_name' => 'France'],
        ['english_name' => 'Finland', 'local_name' => 'Suomi'],
        ['english_name' => 'Germany', 'local_name' => 'Deutschland'],
        ['english_name' => 'Ireland', 'local_name' => 'Eire'],
        ['english_name' => 'Italy', 'local_name' => 'Italia'],
        ['english_name' => 'Japan', 'local_name' => 'Nihon'],
        ['english_name' => 'Netherlands', 'local_name' => 'Nederland'],
        ['english_name' => 'Norway', 'local_name' => 'Norge'],
        ['english_name' => 'Poland', 'local_name' => 'Polska'],
        ['english_name' => 'Portugal', 'local_name' => 'Portugal'],
        ['english_name' => 'Scotland', 'local_name' => 'Scotland'],
        ['english_name' => 'Spain', 'local_name' => 'Espana'],
        ['english_name' => 'Sweden', 'local_name' => 'Sverige'],
        ['english_name' => 'Wales', 'local_name' => 'Wales'],
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $territory = fake()->unique()->randomElement($this->territories);

        return [
            'english_name' => $territory['english_name'],
            'local_name' => $territory['local_name'],
        ];
    }
}
