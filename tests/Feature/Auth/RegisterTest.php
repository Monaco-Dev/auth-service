<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\BrokerLicense;
use App\Models\User;

class RegisterTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    /**
     * Test successful response.
     */
    public function test_success(): void
    {
        $user = User::factory()->make();
        $brokerLicense = BrokerLicense::factory()->make(['user_id' => $user->id]);
        $password = $this->faker()->password(8, 8) . 'Aa1!';

        $payload = [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'username' => $user->username,
            'email' => $user->email,
            'password' => $password,
            'password_confirmation' => $password,
            'broker_license_number' => $brokerLicense->license_number
        ];

        $this->withHeaders(['Accept' => 'application/json'])
            ->post('/api/register', $payload)
            ->assertStatus(200);
    }

    /**
     * Test invalid response.
     */
    public function test_invalid(): void
    {
        $this->withHeaders(['Accept' => 'application/json'])
            ->post('/api/register')
            ->assertStatus(422);
    }
}
