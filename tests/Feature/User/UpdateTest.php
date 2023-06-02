<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use Facades\App\Repositories\Contracts\UserRepositoryInterface as UserRepository;

class UpdateTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test successful response.
     */
    public function test_success(): void
    {
        $user = User::factory()->hasBrokerLicense()->create();
        $dummy = User::factory()->make();
        $auth = UserRepository::authenticate($user->username, 'Password123!');

        $payload = [
            'first_name' => $dummy->first_name,
            'last_name' => $dummy->last_name,
            'username' => $dummy->username,
            'email' => $dummy->email
        ];

        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $auth['access_token']
        ])
            ->put(route('user.update', $user->id), $payload)
            ->assertStatus(200);
    }

    /**
     * Test unauthenticated response.
     */
    public function test_unauthenticated(): void
    {
        $user = User::factory()->create();

        $this->withHeaders(['Accept' => 'application/json'])
            ->put(route('user.update', $user->id))
            ->assertStatus(401);
    }
}
