<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;

class LoginTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test successful response.
     */
    public function test_success(): void
    {
        $user = User::factory()->create();

        $payload = [
            'login' => $user->username,
            'password' => 'Password123!'
        ];

        $this->withHeaders(['Accept' => 'application/json'])
            ->post(route('auth.login'), $payload)
            ->assertStatus(200);
    }

    /**
     * Test invalid response.
     */
    public function test_invalid(): void
    {
        $this->withHeaders(['Accept' => 'application/json'])
            ->post(route('auth.login'))
            ->assertStatus(422);
    }
}
