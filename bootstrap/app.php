<?php


use App\Http\Middleware\CheckUserRole;


use App\Http\Middleware\Admin;
use App\Http\Middleware\CheckUser;
use App\Http\Middleware\Teacher;
use App\Http\Middleware\VerifiedEmail;


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
        $middleware->alias([

            'CheckUser' => CheckUser::class ,
            'Admin' => Admin::class,
            'Teacher' => Teacher::class

        ]);

        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);



    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
