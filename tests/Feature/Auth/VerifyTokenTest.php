<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;

class VerifyTokenTest extends TestCase
{
    use RefreshDatabase;

    protected $route = 'auth.token.verify';

    /**
     * Test unauthenticated response.
     */
    public function test_unauthenticated(): void
    {
        $this->withHeaders(['Accept' => 'application/json'])
            ->get(route($this->route))
            ->assertUnauthorized();
    }

    /**
     * Test successful response.
     */
    public function test_success(): void
    {
        $this->withoutMiddleware();

        $user = User::factory()->create();

        $this->actingAs($user)
            ->withHeaders(['Accept' => 'application/json'])
            ->get(route($this->route))
            ->assertOk()
            ->assertValid();
    }
}
