<?php

namespace App\Http\Middleware;

use App\Services\SettingsService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthConfig
{
    public function __construct(private SettingsService $settingsService) {}

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $auth = $this->settingsService->getSetting([
            'type' => 'auth',
            'sub_type' => 'google'
        ])['fields'];

        // Google Auth configuration
        config([
            'services.google.status' => $auth['active'],
            'services.google.client_id' => $auth['client_id'],
            'services.google.client_secret' => $auth['client_secret'],
            'services.google.redirect' => $auth['redirect'],
        ]);

        return $next($request);
    }
}
