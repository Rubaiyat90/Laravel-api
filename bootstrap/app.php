<?php

use App\Http\Middleware\HoldingDueApi;
use App\Http\Middleware\SandboxSanctum;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('api')
                ->prefix('vendors')
                ->group(base_path('routes/vendor.php'));

            Route::prefix('sandbox-vendors')
                ->middleware('api')
                ->group(base_path('routes/sandbox-vendors.php'));

        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        //$middleware->redirectGuestsTo(fn (Request $request) => route('admin.auth.login'));
        $middleware->alias([
            'sandbox' => SandboxSanctum::class,
            'holdingDueApi' => HoldingDueApi::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
