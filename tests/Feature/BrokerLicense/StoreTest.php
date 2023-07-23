<?php

namespace Tests\Feature\BrokerLicense;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\BrokerLicense;
use App\Models\User;

class StoreTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test successful response.
     */
    public function test_success(): void
    {
        $this->withoutMiddleware();

        $user = User::factory()->hasBrokerLicense()->create();
        $brokerLicense = BrokerLicense::factory()->make();

        $payload = [
            'license_number' => $brokerLicense->license_number,
            'expiration_date' => $brokerLicense->expiration_date
        ];

        $this->actingAs($user)
            ->withHeaders(['Accept' => 'application/json'])
            ->post(route('brokerLicenses.store'), $payload)
            ->assertStatus(200);
    }

    /**
     * Test unauthenticated response.
     */
    public function test_unauthenticated(): void
    {
        $this->withHeaders(['Accept' => 'application/json'])
            ->post(route('brokerLicenses.store'))
            ->assertStatus(401);
    }
}
