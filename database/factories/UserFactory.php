<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate the user has an admin email address.
     * This will give admin panel access if user email is verified.
     * Optionally accepts a local part for the email address,
     * otherwise a fake first name will be generated and used in its place.
     */
    public function adminEmail(string $localPart = ''): static
    {
        $emailLocalPart = $localPart
            ? $localPart
            : fake()->unique()->firstName();

        $emailDomainPart = config('app.admin_email_domain')
            ? config('app.admin_email_domain')
            : 'admin.com';

        return $this->state(fn (array $attributes) => [
            'email' => $emailLocalPart.'@'.$emailDomainPart,
        ]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
