<?php

namespace Tests\Feature\Auth;

use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;

class ResetPasswordTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    /**
     * A basic feature test example.
     */
    public function test_success(): void
    {
        $user = User::factory()->create();
        $token = Password::broker()->createToken($user);
        $password = $this->faker()->password(8, 8) . 'Aa1!';

        $payload = [
            'token' => $token,
            'email' => $user->email,
            'password' => $password,
            'password_confirmation' => $password
        ];

        $this->withHeaders(['Accept' => 'application/json'])
            ->post(route('password.update'), $payload)
            ->assertStatus(200);
    }

    /**
     * Test invalid response.
     */
    public function test_invalid(): void
    {
        $this->withHeaders(['Accept' => 'application/json'])
            ->post(route('password.update'))
            ->assertStatus(422);
    }
}
