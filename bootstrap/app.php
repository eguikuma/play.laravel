<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        \Examples\Providers\ExposeModule::class,
        \App\Providers\ResponseServiceProvider::class,
        \App\Providers\TelescopeServiceProvider::class,
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
    )
    ->withMiddleware(function () {})
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (Throwable $e) {
            switch (true) {
                case $e instanceof NotFoundHttpException:
                    return Response::not_found('存在しないパスです。');
            }
        });
    })
    ->create();

return $app;
