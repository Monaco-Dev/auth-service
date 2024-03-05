<?php

namespace Tests\Feature\Follow;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Arr;

use App\Models\License;
use App\Models\User;

class UnfollowTest extends TestCase
{
    use RefreshDatabase;

    protected $route = 'follows.unfollow';

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
    public function test_unverified_license(): void
    {
        $user = User::factory()
            ->has(License::factory()->unverified())
            ->create();

        $auth = $this->login($user->email);

        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . Arr::get($auth, 'access_token')
        ])
            ->delete(route($this->route, $user))
            ->assertForbidden()
            ->assertSeeText('Your license is not verified');
    }

    /**
     * Test expired license response
     */
    public function test_expired_license(): void
    {
        $user = User::factory()
            ->has(License::factory()->expired())
            ->create();

        $auth = $this->login($user->email);

        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . Arr::get($auth, 'access_token')
        ])
            ->delete(route($this->route, $user))
            ->assertForbidden()
            ->assertSeeText('Your license is expired');
    }

    /**
     * Test successful response.
     */
    public function test_success(): void
    {
        $user = User::factory()
            ->hasLicense()
            ->hasFollowing()
            ->create();

        $auth = $this->login($user->email);

        $follow = $user->following()->first();

        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . Arr::get($auth, 'access_token')
        ])
            ->delete(route($this->route, $follow))
            ->assertOk();
    }

    /**
     * Test unauthorized response.
     */
    public function test_unauthorized(): void
    {
        $user = User::factory()
            ->hasLicense()
            ->count(2)
            ->create();

        $auth = $this->login($user[0]->email);

        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . Arr::get($auth, 'access_token')
        ])
            ->delete(route($this->route, $user[1]))
            ->assertForbidden();
    }
}
