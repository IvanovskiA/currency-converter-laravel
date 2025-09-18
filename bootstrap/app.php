<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $e, $request) {
            if ($request->is('api/*') || $request->wantsJson()) {
                $status = 500;
                $message = 'Server error';

                if ($e instanceof \Illuminate\Validation\ValidationException) {
                    return response()->json([
                        'errors' => $e->errors(),
                    ], 422);
                }

                if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface) {
                    $status = $e->getStatusCode();
                    $message = $e->getMessage() ?: $message;
                } else {
                    if (app()->hasDebugModeEnabled()) {
                        $message = $e->getMessage() ?: $message;
                    }
                }

                return response()->json([
                    'error' => [
                        'message' => $message,
                    ]
                ], $status);
            }
        });
    })->create();
