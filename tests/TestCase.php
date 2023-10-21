<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Notification;
use Event;

use App\Models\User;
use App\Repositories\Support\Auth\AuthRequest;
use Database\Seeders\OauthClientSeeder;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Run a specific seeder before each test.
     *
     * @var string
     */
    protected $seeder = OauthClientSeeder::class;

    protected $route;

    /**
     * Set up the test case.
     */
    protected function setUp(): void
    {
        parent::setUp();

        Notification::fake();
        Event::fake();
    }

    /**
     * Request for password grant access token.
     * 
     * @param User
     * @return json
     */
    protected function login($email)
    {
        return collect(
            (new AuthRequest)->getToken($email, 'Password123!')
        );
    }
}
