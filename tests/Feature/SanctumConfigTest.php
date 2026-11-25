<?php

namespace Tests\Feature;

use Tests\TestCase;

class SanctumConfigTest extends TestCase
{
    public function test_sanctum_middleware_config_uses_sanctum_4_keys(): void
    {
        $middleware = config('sanctum.middleware');

        $this->assertArrayHasKey('authenticate_session', $middleware);
        $this->assertArrayHasKey('encrypt_cookies', $middleware);
        $this->assertArrayHasKey('validate_csrf_token', $middleware);
        $this->assertArrayNotHasKey('verify_csrf_token', $middleware);
    }

    public function test_sanctum_middleware_config_maps_to_expected_classes(): void
    {
        $this->assertSame(
            \Laravel\Sanctum\Http\Middleware\AuthenticateSession::class,
            config('sanctum.middleware.authenticate_session')
        );
        $this->assertSame(
            \App\Http\Middleware\EncryptCookies::class,
            config('sanctum.middleware.encrypt_cookies')
        );
        $this->assertSame(
            \App\Http\Middleware\VerifyCsrfToken::class,
            config('sanctum.middleware.validate_csrf_token')
        );
    }

    public function test_api_user_route_is_protected_by_auth_sanctum(): void
    {
        $route = collect(app('router')->getRoutes()->getRoutes())
            ->first(fn ($route) => $route->uri() === 'api/user' && in_array('GET', $route->methods(), true));

        $this->assertNotNull($route, 'GET api/user route is not registered.');
        $this->assertContains('auth:sanctum', $route->gatherMiddleware());
    }
}
