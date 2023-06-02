<?php

namespace Tests\Feature\Connection;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\ConnectionInvitation;
use Facades\App\Repositories\Contracts\UserRepositoryInterface as UserRepository;

class ConnectTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test successful response.
     */
    public function test_success(): void
    {
        $user = User::factory()->hasBrokerLicense()->create();
        $network = User::factory()->hasBrokerLicense()->create();

        ConnectionInvitation::factory()->create([
            'user_id' => $network->id,
            'invitation_user_id' => $user->id
        ]);

        $auth = UserRepository::authenticate($user->username, 'Password123!');

        $payload = [
            'user_id' => $network->id
        ];

        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $auth['access_token']
        ])
            ->post(route('connection.connect'), $payload)
            ->assertStatus(200);
    }

    /**
     * Test invalid response.
     */
    public function test_invalid(): void
    {
        $user = User::factory()->hasBrokerLicense()->create();
        $auth = UserRepository::authenticate($user->username, 'Password123!');

        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $auth['access_token']
        ])
            ->post(route('connection.connect'))
            ->assertStatus(422);
    }

    /**
     * Test unauthenticated response.
     */
    public function test_unauthenticated(): void
    {
        $this->withHeaders(['Accept' => 'application/json'])
            ->post(route('connection.connect'))
            ->assertStatus(401);
    }
}
