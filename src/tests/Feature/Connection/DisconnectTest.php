<?php

namespace Tests\Feature\Connection;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Arr;

use App\Models\BrokerLicense;
use App\Models\User;

class DisconnectTest extends TestCase
{
    use RefreshDatabase;

    protected $route = 'connections.disconnect';

    /**
     * Test unauthenticated response.
     */
    public function test_unauthenticated(): void
    {
        $this->withHeaders(['Accept' => 'application/json'])
            ->delete(route($this->route, 1))
            ->assertUnauthorized();
    }

    /**
     * Test unverified email response
     */
    public function test_unverified_email(): void
    {
        $user = User::factory()->unverified()->create();

        $auth = $this->login($user->email);

        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . Arr::get($auth, 'access_token')
        ])
            ->delete(route($this->route, $user))
            ->assertForbidden()
            ->assertSeeText('Your email address is not verified');
    }

    /**
     * Test unverified license response
     */
    // public function test_unverified_license(): void
    // {
    //     $user = User::factory()
    //         ->has(BrokerLicense::factory()->unverified())
    //         ->create();

    //     $auth = $this->login($user->email);

    //     $this->withHeaders([
    //         'Accept' => 'application/json',
    //         'Authorization' => 'Bearer ' . Arr::get($auth, 'access_token')
    //     ])
    //         ->delete(route($this->route, $user))
    //         ->assertForbidden()
    //         ->assertSeeText('Your license number is not verified');
    // }

    /**
     * Test expired license response
     */
    // public function test_expired_license(): void
    // {
    //     $user = User::factory()
    //         ->has(BrokerLicense::factory()->expired())
    //         ->create();

    //     $auth = $this->login($user->email);

    //     $this->withHeaders([
    //         'Accept' => 'application/json',
    //         'Authorization' => 'Bearer ' . Arr::get($auth, 'access_token')
    //     ])
    //         ->delete(route($this->route, $user))
    //         ->assertForbidden()
    //         ->assertSeeText('Your license number is expired');
    // }

    /**
     * Test successful response.
     */
    public function test_success(): void
    {
        $user = User::factory()
            ->hasBrokerLicense()
            ->create();

        $network = User::factory()
            ->hasBrokerLicense()
            ->create();

        $user->connections()->attach($network);
        $network->connections()->attach($user);

        $auth = $this->login($user->email);

        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . Arr::get($auth, 'access_token')
        ])
            ->delete(route(
                $this->route,
                $network->id
            ))
            ->assertOk();
    }

    /**
     * Test unauthorized response.
     */
    public function test_unauthorized(): void
    {
        $user = User::factory()->hasBrokerLicense()->create();
        $dummy = User::factory()->hasBrokerLicense()->create();

        $auth = $this->login($user->email);

        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . Arr::get($auth, 'access_token')
        ])
            ->delete(route($this->route, $dummy->id))
            ->assertForbidden();
    }
}
