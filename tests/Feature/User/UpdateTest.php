<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;

class UpdateTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test successful response.
     */
    public function test_success(): void
    {
        $this->withoutMiddleware();

        $user = User::factory()->hasBrokerLicense()->create();
        $dummy = User::factory()->make();

        $payload = [
            'first_name' => $dummy->first_name,
            'last_name' => $dummy->last_name,
            'username' => $dummy->username,
            'email' => $dummy->email
        ];

        $this->actingAs($user)
            ->withHeaders(['Accept' => 'application/json'])
            ->put(route('users.update', $user->id), $payload)
            ->assertStatus(200);
    }

    /**
     * Test unauthenticated response.
     */
    public function test_unauthenticated(): void
    {
        $user = User::factory()->create();

        $this->withHeaders(['Accept' => 'application/json'])
            ->put(route('users.update', $user->id))
            ->assertStatus(401);
    }

    /**
     * Test not found response.
     */
    public function test_not_found(): void
    {
        $this->withoutMiddleware();

        $user = User::factory()->hasBrokerLicense()->create();

        $this->actingAs($user)
            ->withHeaders(['Accept' => 'application/json'])
            ->put(route('users.update', 0))
            ->assertStatus(404);
    }

    /**
     * Test unauthorized response.
     */
    public function test_unauthorized(): void
    {
        $this->withoutMiddleware();

        $user = User::factory()->hasBrokerLicense()->create();
        $dummy = User::factory()->create();

        $this->actingAs($user)
            ->withHeaders(['Accept' => 'application/json'])
            ->put(route('users.update', $dummy->id))
            ->assertStatus(403);
    }
}
