<?php

namespace Tests\Feature\Connection;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\ConnectionInvitation;

class ConnectTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test successful response.
     */
    public function test_success(): void
    {
        $this->withoutMiddleware();

        $user = User::factory()->hasBrokerLicense()->create();
        $network = User::factory()->hasBrokerLicense()->create();

        ConnectionInvitation::factory()->create([
            'user_id' => $network->id,
            'invitation_user_id' => $user->id
        ]);

        $payload = [
            'user_id' => $network->id
        ];

        $this->actingAs($user)
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('connections.connect'), $payload)
            ->assertStatus(200);
    }

    /**
     * Test invalid response.
     */
    public function test_invalid(): void
    {
        $this->withoutMiddleware();

        $user = User::factory()->hasBrokerLicense()->create();

        $this->actingAs($user)
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('connections.connect'))
            ->assertStatus(422);
    }

    /**
     * Test unauthenticated response.
     */
    public function test_unauthenticated(): void
    {
        $this->withHeaders(['Accept' => 'application/json'])
            ->post(route('connections.connect'))
            ->assertStatus(401);
    }
}
