<?php

namespace Database\Factories;

use App\Enums\Gender;
use App\Models\AuthorisationStatus;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
{
    /**
     * Qualifications to select from.
     *
     * @var string[]
     */
    private $qualifications = ['Cert', 'Dip', 'AdvDip', 'AssocDeg', 'BA', 'MD', 'PhD'];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $qualifications = fake()->randomElements($this->qualifications, random_int(2, 4));

        return [
            // 'authorisation_status' => '',
            'business_email' => fake()->unique()->email(),
            'business_phone' => fake()->unique()->phoneNumber(),
            'business_website' => fake()->url(),
            // 'territory_of_origin_id' => '',
            // 'territory_of_residence_id' => '',
            'description' => fake()->paragraph(),
            'gender' => Gender::random()->value,
            'gives_video_lessons' => fake()->boolean(),
            'name' => fake()->unique()->name(),
            'profile_image_path' => fake()->imageUrl(),
            'qualification_string' => implode(', ', $qualifications),
            'teaches_at_cvi' => fake()->boolean(),
            // 'user_id' => '',
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Teacher $teacher) {
            // create an authorisation status of unauthorised
            $teacher->authorisationStatuses()->create(
                AuthorisationStatus::factory()
                    ->make()
                    ->toArray()
            );
        });
    }

    /**
     * Indicate that the teacher has the status 'Authorised'.
     *
     * @return Factory<Teacher>
     */
    public function authorised(): Factory
    {
        return $this->afterCreating(function (Teacher $teacher) {
            $teacher->authorisationStatuses()->create(
                AuthorisationStatus::factory()
                    ->authorised()
                    ->make()
                    ->toArray()
            );
        });
    }

    /**
     * Indicate that the teacher has the status 'Expired'.
     *
     * @return Factory<Teacher>
     */
    public function expired(): Factory
    {
        return $this->afterCreating(function (Teacher $teacher) {
            $teacher->authorisationStatuses()->create(
                AuthorisationStatus::factory()
                    ->expired()
                    ->make()
                    ->toArray()
            );
        });
    }
}
