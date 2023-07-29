<?php

namespace Tests\Feature\Connection;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Connection;
use App\Models\User;

class SearchTest extends TestCase
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

        Connection::factory()->create([
            'user_id' => $user->id,
            'connection_user_id' => $network->id
        ]);

        $payload = [
            'search' => null
        ];

        $this->actingAs($user)
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('connections.search'), $payload)
            ->assertStatus(200);
    }

    /**
     * Test unauthenticated response.
     */
    public function test_unauthenticated(): void
    {
        $this->withHeaders(['Accept' => 'application/json'])
            ->post(route('connections.search'))
            ->assertStatus(401);
    }
}
