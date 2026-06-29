<?php

namespace Tests;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Setup default middlewares that should be skipped during testing.
     * CSRF verification tidak relevan di HTTP test karena request tidak
     * membawa cookie session nyata — cukup verifikasi logika bisnis saja.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Nonaktifkan CSRF verification untuk semua feature test
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }
}
