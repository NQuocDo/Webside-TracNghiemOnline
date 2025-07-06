<?php

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\Middleware\Authenticate;
use App\Providers\ComposerServiceProvider;
use Illuminate\Database\QueryException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->alias([
            'auth' => Authenticate::class,
            'role' => RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (Throwable $e, $request) {
            \Log::error('Lỗi hệ thống: ' . $e->getMessage());
            return response()->view('error', [], 503);
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withProviders([
        ComposerServiceProvider::class,
    ])->create();

