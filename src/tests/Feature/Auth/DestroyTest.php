<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Arr;

use App\Models\User;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    protected $route = 'auth.destroy';

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
     * Test successful response.
     */
    public function test_success(): void
    {
        $auth = $this->login(User::factory()->hasLicense()->create()->email);

        $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . Arr::get($auth, 'access_token')
        ])
            ->post(route($this->route), ['password' => 'Password123!'])
            ->assertOk();
    }
}
