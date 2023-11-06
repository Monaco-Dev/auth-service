<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Arr;

use App\Models\BrokerLicense;
use App\Models\User;

class DeactivateTest extends TestCase
{
    use RefreshDatabase;

    protected $route = 'auth.deactivate';

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
    // public function test_unverified_license(): void
    // {
    //     $auth = $this->login(
    //         User::factory()
    //             ->has(BrokerLicense::factory()->unverified())
    //             ->create()
    //             ->email
    //     );

    //     $this->withHeaders([
    //         'Accept' => 'application/json',
    //         'Authorization' => 'Bearer ' . Arr::get($auth, 'access_token')
    //     ])
    //         ->post(route($this->route))
    //         ->assertForbidden()
    //         ->assertSeeText('Your license number is not verified');
    // }

    /**
     * Test expired license response
     */
    // public function test_expired_license(): void
    // {
    //     $auth = $this->login(
    //         User::factory()
    //             ->has(BrokerLicense::factory()->expired())
    //             ->create()
    //             ->email
    //     );

    //     $this->withHeaders([
    //         'Accept' => 'application/json',
    //         'Authorization' => 'Bearer ' . Arr::get($auth, 'access_token')
    //     ])
    //         ->post(route($this->route))
    //         ->assertForbidden()
    //         ->assertSeeText('Your license number is expired');
    // }

    /**
     * Test successful response.
     */
    public function test_success(): void
    {
        $this->withoutMiddleware();

        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route($this->route))
            ->assertOk();
    }
}
