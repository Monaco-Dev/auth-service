<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Arr;

use App\Models\License;
use App\Models\User;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    protected $route = 'users.show';

    /**
     * Test unauthenticated response.
     */
    public function test_unauthenticated(): void
    {
        $this->withHeaders(['Accept' => 'application/json'])
            ->get(route($this->route, 'uuid'))
            ->assertUnauthorized();
    }

    /**
     * Test unverified email response
     */
    public function test_unverified_email(): void
    {
        $auth = $this->login(
            User::factory()
                ->unverified()
                ->create()
                ->email
        );

        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . Arr::get($auth, 'access_token')
        ])
            ->get(route($this->route, 'uuid'))
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
            ->get(route($this->route, $user->uuid))
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
            ->get(route($this->route, $user->uuid))
            ->assertForbidden()
            ->assertSeeText('Your license is expired');
    }

    /**
     * Test successful response.
     */
    public function test_success(): void
    {
        $this->withoutMiddleware();

        $user = User::factory()->hasLicense()->create();

        $this->actingAs($user)
            ->withHeaders(['Accept' => 'application/json'])
            ->get(route($this->route, $user->uuid))
            ->assertOk();
    }

    /**
     * Test unauthenticated response.
     */
    public function test_not_found(): void
    {
        $user = User::factory()->hasLicense()->create();

        $auth = $this->login($user->email);

        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . Arr::get($auth, 'access_token')
        ])
            ->get(route($this->route, 'uuid'))
            ->assertNotFound();
    }
}
