<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\TrustProxies;
use App\Http\Middleware\JsonResponse;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(TrustProxies::class);
        $middleware->alias([
            'json.response' => JsonResponse::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Handle API exceptions to return JSON instead of HTML
        $exceptions->render(function (\Throwable $e, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                // Special handling for authentication errors - only for actual auth failures
                if ($e instanceof \Illuminate\Auth\AuthenticationException ||
                    str_contains($e->getMessage(), 'Unauthenticated') || 
                    str_contains($e->getMessage(), 'unauthenticated') ||
                    (str_contains($e->getMessage(), 'authentication') && !str_contains($e->getMessage(), 'Failed to authenticate')) ||
                    (str_contains($e->getMessage(), 'sanctum') && !str_contains($e->getMessage(), 'Failed to authenticate')) ||
                    (str_contains($e->getMessage(), 'Sanctum') && !str_contains($e->getMessage(), 'Failed to authenticate'))) {
                    return response()->json([
                        'message' => 'Unauthenticated',
                        'error' => 'Authentication required'
                    ], 401);
                }

                // Handle other specific exceptions
                if ($e instanceof \Illuminate\Validation\ValidationException) {
                    return response()->json([
                        'message' => 'Validation failed',
                        'errors' => $e->errors()
                    ], 422);
                }

                if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                    return response()->json([
                        'message' => 'Resource not found',
                        'error' => 'The requested resource does not exist'
                    ], 404);
                }

                if ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                    return response()->json([
                        'message' => 'Route not found',
                        'error' => 'The requested endpoint does not exist'
                    ], 404);
                }

                if ($e instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
                    return response()->json([
                        'message' => 'Method not allowed',
                        'error' => 'The HTTP method is not supported for this endpoint'
                    ], 405);
                }

                // Handle any other exceptions
                return response()->json([
                    'message' => 'Internal server error',
                    'error' => config('app.debug') ? $e->getMessage() : 'Something went wrong'
                ], 500);
            }
        });
    })->create();
