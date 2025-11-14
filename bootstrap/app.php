<?php

use App\Http\Middleware\AdminMiddleware;
use App\Livewire\ErrorPage;
use App\Livewire\Front\NotFoundPage;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: [
            __DIR__ . '/../routes/public.php',  // frontend routes
            __DIR__ . '/../routes/web.php', // Admin routes

        ],
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => AdminMiddleware::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function(NotFoundHttpException $e, $request){
            return App::call(NotFoundPage::class);
        });

       $exceptions->render(function (HttpException $e, $request) {
            $code = $e->getStatusCode();

            if (in_array($code, [403, 419, 429])) {
                return App::call(ErrorPage::class, ['code' => $code]);
            }

            return null; // allow next handler
        });

        $exceptions->render(function (Throwable $e, $request) {

            // prevent overriding 404/403/etc
            if ($e instanceof HttpException) {
                return null;
            }

            // only override in production
            if (app()->environment('production')) {
                return App::call(ErrorPage::class, ['code' => 500]);
            }

            return null; // let Laravel debug page show in local
        });

    })->create();

