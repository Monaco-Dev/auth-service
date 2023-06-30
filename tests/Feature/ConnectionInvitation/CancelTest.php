<?php

namespace Tests\Feature\ConnectionInvitation;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\ConnectionInvitation;
use App\Models\User;

class CancelTest extends TestCase
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
            'user_id' => $user->id,
            'invitation_user_id' => $network->id
        ]);

        $this->actingAs($user)
            ->withHeaders(['Accept' => 'application/json'])
            ->delete(route('connection-invitations.cancel', $network->id))
            ->assertStatus(200);
    }

    /**
     * Test unauthenticated response.
     */
    public function test_unauthenticated(): void
    {
        $network = User::factory()->hasBrokerLicense()->create();

        $this->withHeaders(['Accept' => 'application/json'])
            ->delete(route('connection-invitations.cancel', $network->id))
            ->assertStatus(401);
    }

    /**
     * Test unauthorized response.
     */
    public function test_unauthorized(): void
    {
        $this->withoutMiddleware();

        $user = User::factory()->hasBrokerLicense()->create();
        $network = User::factory()->hasBrokerLicense()->create();

        $this->actingAs($user)
            ->withHeaders(['Accept' => 'application/json'])
            ->delete(route('connection-invitations.cancel', $network->id))
            ->assertStatus(403);
    }
}
