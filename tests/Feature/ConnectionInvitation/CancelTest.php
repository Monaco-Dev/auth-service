<?php

namespace Tests\Feature\ConnectionInvitation;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\ConnectionInvitation;
use App\Models\User;
use Facades\App\Repositories\Contracts\UserRepositoryInterface as UserRepository;

class CancelTest extends TestCase
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
            'user_id' => $user->id,
            'invitation_user_id' => $network->id
        ]);

        $auth = UserRepository::authenticate($user->username, 'Password123!');

        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $auth['access_token']
        ])
            ->delete(route('connection-invitation.cancel', $network->id))
            ->assertStatus(200);
    }
}
