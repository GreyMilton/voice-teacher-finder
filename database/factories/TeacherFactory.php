<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
{
    private $genders = ['male', 'female', 'non-binary'];

    private $qualifications = ['Cert', 'Dip', 'AdvDip', 'AssocDeg', 'BA', 'MD', 'PhD'];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $qualifications = fake()->randomElements($this->qualifications, mt_rand(2, 4));

        return [
            // 'authorisation_cohort_id' => '',
            'business_email' => fake()->email(),
            'business_phone' => fake()->phoneNumber(),
            'business_website' => fake()->url(),
            // 'country_of_origin_id' => '',
            // 'country_of_residence_id' => '',
            'description' => fake()->paragraph(),
            'gender' => fake()->randomElement($this->genders),
            'gives_video_lessons' => fake()->boolean(),
            'name' => fake()->unique()->name(),
            'profile_image_path' => fake()->imageUrl(),
            'qualification_string' => implode(', ', $qualifications),
            'teaches_at_cvi' => fake()->boolean(),
            // 'user_id' => '',
        ];
    }
}
