<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;

class ShowTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test successful response.
     */
    public function test_success(): void
    {
        $this->withoutMiddleware();

        $user = User::factory()->hasBrokerLicense()->create();

        $this->actingAs($user)
            ->withHeaders(['Accept' => 'application/json'])
            ->get(route('users.show', $user->id))
            ->assertStatus(200);
    }

    /**
     * Test unauthenticated response.
     */
    public function test_unauthenticated(): void
    {
        $user = User::factory()->create();

        $this->withHeaders(['Accept' => 'application/json'])
            ->get(route('users.show', $user->id))
            ->assertStatus(401);
    }

    /**
     * Test unauthenticated response.
     */
    public function test_not_found(): void
    {
        $this->withoutMiddleware();

        $user = User::factory()->make();

        $this->actingAs($user)
            ->withHeaders(['Accept' => 'application/json'])
            ->get(route('users.show', 0))
            ->assertStatus(404);
    }
}
