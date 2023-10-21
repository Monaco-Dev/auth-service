<?php

namespace Tests\Feature\Slug;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Tests\TestCase;

use App\Models\BrokerLicense;
use App\Models\User;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    protected $route = 'slugs.store';

    /**
     * Test unauthenticated response.
     */
    public function test_unauthenticated(): void
    {
        $this->withHeaders(['Accept' => 'application/json'])
            ->post(route($this->route))
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
            ->post(route($this->route))
            ->assertForbidden()
            ->assertSeeText('Your email address is not verified');
    }

    /**
     * Test unverified license response
     */
    public function test_unverified_license(): void
    {
        $auth = $this->login(
            User::factory()
                ->has(BrokerLicense::factory()->unverified())
                ->create()
                ->email
        );

        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . Arr::get($auth, 'access_token')
        ])
            ->post(route($this->route))
            ->assertForbidden()
            ->assertSeeText('Your license number is not verified');
    }

    /**
     * Test expired license response
     */
    public function test_expired_license(): void
    {
        $auth = $this->login(
            User::factory()
                ->has(BrokerLicense::factory()->expired())
                ->create()
                ->email
        );

        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . Arr::get($auth, 'access_token')
        ])
            ->post(route($this->route))
            ->assertForbidden()
            ->assertSeeText('Your license number is expired');
    }

    /**
     * Test successful response.
     */
    public function test_success(): void
    {
        $this->withoutMiddleware();

        $user = User::factory()->hasBrokerLicense()->create();

        $payload = ['slug' => fake()->slug()];

        $this->actingAs($user)
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route($this->route), $payload)
            ->assertOk();
    }

    /**
     * Test invalid response.
     */
    public function test_invalid(): void
    {
        $this->withoutMiddleware();

        $user = User::factory()->hasBrokerLicense()->hasSlugs()->create();
        $dummy = User::factory()->hasBrokerLicense()->hasSlugs()->create();

        $payload = ['slug' => $dummy->slugs()->first()->id];

        $this->actingAs($user)
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route($this->route), $payload)
            ->assertUnprocessable();
    }
}
