<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Notification;
use Event;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Set up the test case.
     */
    protected function setUp(): void
    {
        parent::setUp();

        Notification::fake();
        Event::fake();
    }
}
