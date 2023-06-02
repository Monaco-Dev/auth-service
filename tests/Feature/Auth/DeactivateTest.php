<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use Facades\App\Repositories\Contracts\UserRepositoryInterface as UserRepository;

class DeactivateTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test successful response.
     */
    public function test_success(): void
    {
        $user = User::factory()->create();

        $auth = UserRepository::authenticate($user->username, 'Password123!');

        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $auth['access_token']
        ])
            ->delete(route('auth.deactivate'))
            ->assertStatus(200);
    }

    /**
     * Test unauthenticated response.
     */
    public function test_unauthenticated(): void
    {
        $this->withHeaders(['Accept' => 'application/json'])
            ->delete(route('auth.deactivate'))
            ->assertStatus(401);
    }
}
