<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // $middleware->api(prepend: [
        //     \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        // ]);

        // $middleware->redirectTo(
        //     guests: '/admin/login',
        //     users: '/dashboard'
        // );
        // // Using a string
        // $middleware->remove(\Illuminate\Http\Middleware\ValidatePostSize::class);
        // // Or removing multiple default middleware
        // $middleware->remove([
        //     \Illuminate\Http\Middleware\TrustProxies::class,
        //     \Illuminate\Http\Middleware\HandleCors::class,
        // ]);

        // $middleware->encryptCookies(except: [
        //     'abc',
        //     'test',
        // ]);

        // $middleware->validateCsrfTokens(except: [
        //     '/stripe/*',
        //     '/stripe/callback',
        // ]);
        // $middleware->validateSignatures(except: [
        //     '/api/*',
        // ]);

        // $middleware->convertEmptyStringsToNull(except: [
        //     fn ($request) => $request->path() === 'admin/dashboard',
        // ]);
        //  $middleware->trimStrings(except: [
        //     '/test',
        // ]);

        $middleware->alias([
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
            'jwt.verify' => \App\Http\Middleware\JwtMiddleware::class,//
        ]);


    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
