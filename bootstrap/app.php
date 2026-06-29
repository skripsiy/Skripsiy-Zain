<?php

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->booted(function () {
        // Fix S-1: Rate limiting pada endpoint login untuk mencegah brute-force
        // Maksimal 5 percobaan per menit, dikunci per kombinasi IP + username/email
        RateLimiter::for('login', function (Request $request) {
            $identifier = strtolower($request->input('username', $request->input('email', '')));
            return [
                Limit::perMinute(5)->by($request->ip() . '|' . $identifier)
                    ->response(function () {
                        return back()
                            ->withErrors(['username' => 'Terlalu banyak percobaan login. Silakan coba lagi dalam 1 menit.'])
                            ->withInput();
                    }),
            ];
        });
    })
    ->create();
