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
        $middleware->throttleApi();

        // Custom rate limiters
        $middleware->alias([
            'throttle.login' => \Illuminate\Routing\Middleware\ThrottleRequests::class . ':login',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $e, $request) {
            if ($request->is('api/*')) {
                // Handle authentication exceptions
                if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthenticated. Please login to access this resource.',
                    ], 401);
                }

                // Handle route not found (usually caused by unauthenticated redirect)
                if ($e instanceof \Symfony\Component\Routing\Exception\RouteNotFoundException) {
                    if (str_contains($e->getMessage(), 'login')) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Unauthenticated. Please login to access this resource.',
                        ], 401);
                    }
                }

                // Handle validation exceptions
                if ($e instanceof \Illuminate\Validation\ValidationException) {
                    return response()->json([
                        'success' => false,
                        'message' => $e->getMessage(),
                        'errors' => $e->errors(),
                    ], 422);
                }

                // Handle authorization exceptions
                if ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Forbidden. You do not have permission to access this resource.',
                    ], 403);
                }

                // Handle model not found
                if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Resource not found.',
                    ], 404);
                }

                // Handle HTTP exceptions
                if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
                    return response()->json([
                        'success' => false,
                        'message' => $e->getMessage() ?: 'An error occurred.',
                    ], $e->getStatusCode());
                }

                // Handle all other exceptions
                $statusCode = 500;

                return response()->json([
                    'success' => false,
                    'message' => config('app.debug') ? $e->getMessage() : 'An error occurred. Please try again later.',
                ], $statusCode);
            }
        });
    })->create();
