<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BrokerLicense>
 */
class BrokerLicenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'license_number' => fake()->numerify('#######'),
            'verified_at' => now(),
            'expiration_date' => now()->addYear()
        ];
    }

    /**
     * Indicate that the model should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'verified_at' => null
        ]);
    }

    /**
     * Indicate that the model should be expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'expiration_date' => now()
        ]);
    }
}
