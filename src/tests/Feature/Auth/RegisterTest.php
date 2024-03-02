<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\License;
use App\Models\User;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    protected $route = 'auth.register';

    /**
     * Test successful response.
     */
    public function test_success(): void
    {
        $user = User::factory()->make();
        $license = License::factory()->make(['user_id' => $user->id]);
        $password = fake()->password(8, 8) . 'Aa1!';

        $payload = [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'phone_number' => $user->phone_number,
            'email' => $user->email,
            'password' => $password,
            'password_confirmation' => $password,
            'broker' => [
                'license_number' => $license->license_number,
                'expiration_date' => $license->expiration_date
            ]
        ];

        $this->withHeaders(['Accept' => 'application/json'])
            ->post(route($this->route), $payload)
            ->assertOk()
            ->assertValid();
    }

    /**
     * Test invalid response.
     */
    public function test_invalid(): void
    {
        $payload = [];

        $this->withHeaders(['Accept' => 'application/json'])
            ->post(route($this->route), $payload)
            ->assertUnprocessable()
            ->assertInvalid([
                'first_name' => 'The first name field is required.',
                'last_name' => 'The last name field is required.',
                'email' => 'The email field is required.',
                'password' => 'The password field is required.',
                'phone_number' => 'The phone number field is required.'
            ]);
    }
}
