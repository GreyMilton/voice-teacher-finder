<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Language>
 */
class LanguageFactory extends Factory
{
    /**
     * Languages to select from.
     *
     * @var array<array<string, string>>
     */
    private array $languages = [
        ['english_name' => 'Danish'],
        ['english_name' => 'Dutch'],
        ['english_name' => 'English'],
        ['english_name' => 'French'],
        ['english_name' => 'German'],
        ['english_name' => 'Italian'],
        ['english_name' => 'Japanese'],
        ['english_name' => 'Portugese'],
        ['english_name' => 'Spanish'],
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $language = fake()->unique()->randomElement($this->languages);

        return [
            'english_name' => $language['english_name'],
        ];
    }
}
