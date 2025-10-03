<?php

namespace Database\Factories;

use App\Enums\AuthorisationStatus as EnumsAuthorisationStatus;
use App\Models\AuthorisationStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AuthorisationStatus>
 */
class AuthorisationStatusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'value' => EnumsAuthorisationStatus::Unauthorised,
        ];
    }

    /**
     * Indicate that the status is 'Authorised'.
     *
     * @return Factory<AuthorisationStatus>
     */
    public function authorised(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'value' => EnumsAuthorisationStatus::Authorised,
            ];
        });
    }

    /**
     * Indicate that the status is 'Expired'.
     *
     * @return Factory<AuthorisationStatus>
     */
    public function expired(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'value' => EnumsAuthorisationStatus::Expired,
            ];
        });
    }
}
