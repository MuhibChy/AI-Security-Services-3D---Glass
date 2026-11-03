<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Regression tests for the two security fixes applied during the OWASP audit:
 *
 *  1. Ignition "runnable solutions" must stay disabled. When enabled it exposes
 *     POST /_ignition/execute-solution, which executes arbitrary solutions and is
 *     the vector behind the Ignition RCE family (CVE-2021-3129 and successors).
 *
 *  2. The X-Powered-By header must not be emitted, so the PHP build is not
 *     advertised to clients.
 */
class SecurityHeaderAndIgnitionTest extends TestCase
{
    use RefreshDatabase;

    public function test_ignition_runnable_solutions_are_disabled_by_default(): void
    {
        $this->assertFalse(
            (bool) config('ignition.enable_runnable_solutions'),
            'Ignition runnable solutions must be disabled; enabling them exposes an RCE surface.'
        );
    }

    public function test_ignition_execute_solution_endpoint_is_not_reachable(): void
    {
        $response = $this->postJson('/_ignition/execute-solution', []);

        $this->assertSame(
            404,
            $response->getStatusCode(),
            'The Ignition execute-solution endpoint must not be routable.'
        );
    }

    public function test_ignition_health_check_endpoint_is_not_reachable(): void
    {
        $response = $this->get('/_ignition/health-check');

        $this->assertSame(
            404,
            $response->getStatusCode(),
            'The Ignition health-check endpoint must not be routable.'
        );
    }

    public function test_x_powered_by_header_is_not_exposed(): void
    {
        $response = $this->get('/');

        $this->assertFalse(
            $response->headers->has('X-Powered-By'),
            'The X-Powered-By header must be removed to avoid disclosing the PHP version.'
        );
    }

    public function test_baseline_security_headers_are_still_present(): void
    {
        $response = $this->get('/');

        $this->assertSame('nosniff', $response->headers->get('X-Content-Type-Options'));
        $this->assertSame('DENY', $response->headers->get('X-Frame-Options'));
        $this->assertSame('strict-origin-when-cross-origin', $response->headers->get('Referrer-Policy'));
        $this->assertNotNull($response->headers->get('Content-Security-Policy'));
    }
}
