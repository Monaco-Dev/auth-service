<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Arr;

use App\Models\License;
use App\Models\User;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    protected $route = 'users.update';

    /**
     * Test unauthenticated response.
     */
    public function test_unauthenticated(): void
    {
        $this->withHeaders(['Accept' => 'application/json'])
            ->post(route($this->route, 1))
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
            ->post(route($this->route, $user))
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
            ->post(route($this->route, $user))
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
            ->post(route($this->route, $user))
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
            ->create();

        $auth = $this->login($user->email);

        $payload = [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone_number' => $user->phone_number,
            'password' => 'Password123!'
        ];

        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . Arr::get($auth, 'access_token')
        ])
            ->post(route($this->route, $user->id), $payload)
            ->assertOk();
    }

    /**
     * Test unauthorized response.
     */
    public function test_unauthorized(): void
    {
        $user = User::factory()->hasLicense()->create();
        $dummy = User::factory()->create();

        $auth = $this->login($user->email);

        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . Arr::get($auth, 'access_token')
        ])
            ->post(route($this->route, $dummy->id))
            ->assertForbidden();
    }
}
