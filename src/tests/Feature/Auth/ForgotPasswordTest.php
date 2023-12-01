<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    protected $route = 'password.email';

    /**
     * Test successful response.
     */
    public function test_success(): void
    {
        $user = User::factory()->create();

        $payload = ['email' => $user->email];

        $this->post(route($this->route), $payload)
            ->assertOk()
            ->assertValid();
    }

    /**
     * Test email required response.
     */
    public function test_email_required(): void
    {
        $this->withHeaders(['Accept' => 'application/json'])
            ->post(route($this->route))
            ->assertUnprocessable()
            ->assertSeeText('The email field is required');
    }

    /**
     * Test email format response.
     */
    public function test_email_format(): void
    {
        $payload = ['email' => fake()->userName()];

        $this->withHeaders(['Accept' => 'application/json'])
            ->post(route($this->route), $payload)
            ->assertUnprocessable()
            ->assertSeeText('The email field must be a valid email address');
    }
}
