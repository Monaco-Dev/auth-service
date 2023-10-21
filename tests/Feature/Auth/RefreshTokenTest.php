<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Arr;

use App\Models\User;

class RefreshTokenTest extends TestCase
{
    use RefreshDatabase;

    protected $route = 'auth.token.refresh';

    /**
     * Test successful response.
     */
    public function test_success(): void
    {
        $auth = $this->login(User::factory()->create()->email);

        $payload = ['refresh_token' => Arr::get($auth, 'refresh_token')];

        $this->withHeaders(['Accept' => 'application/json'])
            ->post(route($this->route), $payload)
            ->assertOk()
            ->assertValid();
    }

    /**
     * Test invalid response.
     */
    public function test_invalid(): void
    {
        $this->withHeaders(['Accept' => 'application/json'])
            ->post(route($this->route))
            ->assertUnprocessable()
            ->assertInvalid([
                'refresh_token' => 'The refresh token field is required.'
            ]);
    }
}
