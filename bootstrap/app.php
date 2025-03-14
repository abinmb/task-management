<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\{AdminMiddleware, UserMiddleware,LogExecutionTime};
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Auth\AuthenticationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin'    => AdminMiddleware::class,
            'user'     => UserMiddleware::class,
            'log_data' => LogExecutionTime::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (NotFoundHttpException $e, $request) {
            return response()->json(['status' => false, 'message' => 'Page not found',], 404);
        });
        $exceptions->render(function (AuthenticationException $e, $request) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
        });
    })->create();
