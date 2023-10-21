<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected $route = 'auth.login';

    /**
     * Test successful response.
     */
    public function test_success(): void
    {
        $user = User::factory()->create();

        $payload = [
            'email' => $user->email,
            'password' => 'Password123!',
            'remember_me' => true
        ];

        $this->withHeaders(['Accept' => 'application/json'])
            ->post(route($this->route), $payload)
            ->assertOk()
            ->assertValid();
    }

    /**
     * Test invalid parameters response.
     */
    public function test_invalid_parameters(): void
    {
        $payload = [
            'email' => null,
            'password' => null,
            'remember_me' => 'true'
        ];

        $this->withHeaders(['Accept' => 'application/json'])
            ->post(route($this->route), $payload)
            ->assertUnprocessable()
            ->assertInvalid([
                'email' => ['The email field is required.'],
                'password' => ['The password field is required.'],
                'remember_me' => ['The remember me field must be true or false.']
            ]);
    }

    /**
     * Test invalid credentials response.
     */
    public function test_invalid_credentials(): void
    {
        $user = User::factory()->create();

        $payload = [
            'email' => fake()->safeEmail(),
            'password' => $user->password,
            'remember_me' => true
        ];

        $this->withHeaders(['Accept' => 'application/json'])
            ->post(route($this->route), $payload)
            ->assertUnprocessable()
            ->assertInvalid([
                'email' => ['Invalid Credentials.']
            ]);
    }
}
