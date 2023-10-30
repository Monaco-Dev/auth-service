<?php

namespace Tests\Feature\Slug;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Tests\TestCase;

use App\Models\BrokerLicense;
use App\Models\User;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    protected $route = 'slugs.destroy';

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
        $user = User::factory()
            ->unverified()
            ->hasSlugs()
            ->create();

        $auth = $this->login($user->email);

        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . Arr::get($auth, 'access_token')
        ])
            ->delete(route($this->route, $user->slugs()->first()))
            ->assertForbidden()
            ->assertSeeText('Your email address is not verified');
    }

    /**
     * Test unverified license response
     */
    public function test_unverified_license(): void
    {
        $user = User::factory()
            ->has(BrokerLicense::factory()->unverified())
            ->hasSlugs()
            ->create();

        $auth = $this->login($user->email);

        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . Arr::get($auth, 'access_token')
        ])
            ->delete(route($this->route, $user->slugs()->first()))
            ->assertForbidden()
            ->assertSeeText('Your license number is not verified');
    }

    /**
     * Test expired license response
     */
    public function test_expired_license(): void
    {
        $user = User::factory()
            ->has(BrokerLicense::factory()->expired())
            ->hasSlugs()
            ->create();

        $auth = $this->login($user->email);

        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . Arr::get($auth, 'access_token')
        ])
            ->delete(route($this->route, $user->slugs()->first()))
            ->assertForbidden()
            ->assertSeeText('Your license number is expired');
    }

    /**
     * Test successful response.
     */
    public function test_success(): void
    {
        $user = User::factory()
            ->hasBrokerLicense()
            ->hasSlugs()
            ->create();

        $auth = $this->login($user->email);

        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . Arr::get($auth, 'access_token')
        ])
            ->delete(route($this->route, $user->slugs()->first()))
            ->assertOk();
    }

    /**
     * Test unauthorized response.
     */
    public function test_unauthorized(): void
    {
        $user = User::factory()
            ->hasBrokerLicense()
            ->hasSlugs()
            ->create();

        $dummy = User::factory()
            ->hasBrokerLicense()
            ->hasSlugs()
            ->create();

        $auth = $this->login($user->email);

        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . Arr::get($auth, 'access_token')
        ])
            ->delete(route($this->route, $dummy->slugs()->first()))
            ->assertForbidden();
    }
}
