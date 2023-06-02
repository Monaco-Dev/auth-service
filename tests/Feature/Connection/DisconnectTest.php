<?php

namespace Tests\Feature\Connection;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Connection;
use App\Models\User;
use Facades\App\Repositories\Contracts\UserRepositoryInterface as UserRepository;

class DisconnectTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test successful response.
     */
    public function test_success(): void
    {
        $user = User::factory()->hasBrokerLicense()->create();
        $network = User::factory()->hasBrokerLicense()->create();

        Connection::factory()->create([
            'user_id' => $user->id,
            'connection_user_id' => $network->id
        ]);

        Connection::factory()->create([
            'user_id' => $network->id,
            'connection_user_id' => $user->id
        ]);

        $auth = UserRepository::authenticate($user->username, 'Password123!');

        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $auth['access_token']
        ])
            ->delete(route('connection.disconnect', $network->id))
            ->assertStatus(200);
    }

    /**
     * Test unauthenticated response.
     */
    public function test_unauthenticated(): void
    {
        $network = User::factory()->hasBrokerLicense()->create();

        $this->withHeaders(['Accept' => 'application/json'])
            ->delete(route('connection.disconnect', $network->id))
            ->assertStatus(401);
    }
}
