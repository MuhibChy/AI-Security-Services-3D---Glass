<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        // Apply migrations on each test to ensure clean state
        // Note: We're testing the live application, so we'll create necessary test data
        // rather than relying on full migrations which would affect production
    }
}