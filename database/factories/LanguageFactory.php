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
        ['english_name' => 'Danish', 'native_name' => 'Dansk'],
        ['english_name' => 'Dutch', 'native_name' => 'Nederlands'],
        ['english_name' => 'English', 'native_name' => 'English'],
        ['english_name' => 'French', 'native_name' => 'Francais'],
        ['english_name' => 'German', 'native_name' => 'Deutsch'],
        ['english_name' => 'Italian', 'native_name' => 'Italiano'],
        ['english_name' => 'Japanese', 'native_name' => 'Nihongo'],
        ['english_name' => 'Portugese', 'native_name' => 'Portugues'],
        ['english_name' => 'Spanish', 'native_name' => 'Espanol'],
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
            'native_name' => $language['native_name'],
        ];
    }
}
