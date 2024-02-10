<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Socialite>
 */
class SocialiteFactory extends Factory
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
            'driver_id' => fake()->randomNumber(),
            'driver' => fake()->randomElement(['google', 'facebook', 'linkedin-openid']),
            'token' => fake()->randomLetter(),
            'refresh_token' => fake()->randomLetter(),
            'expires_in' => fake()->unixTime()
        ];
    }
}
