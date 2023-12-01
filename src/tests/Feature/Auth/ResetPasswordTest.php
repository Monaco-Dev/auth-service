<?php

namespace Tests\Feature\Auth;

use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    protected $route = 'password.update';

    /**
     * A basic feature test example.
     */
    public function test_success(): void
    {
        $user = User::factory()->create();
        $token = Password::broker()->createToken($user);
        $password = fake()->password(8, 8) . 'Aa1!';

        $payload = [
            'token' => $token,
            'email' => $user->email,
            'password' => $password,
            'password_confirmation' => $password
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
        $this->withHeaders(['Accept' => 'application/json'])
            ->post(route($this->route))
            ->assertUnprocessable();
    }
}
