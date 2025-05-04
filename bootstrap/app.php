<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Providers\Filament\AdminPanelProvider;
use App\Providers\Filament\LandlordPanelProvider;
use App\Providers\Filament\TenantPanelProvider;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(\App\Http\Middleware\IdentifyTenant::class);
    })
    ->withProviders([
        AdminPanelProvider::class,
        LandlordPanelProvider::class,
        TenantPanelProvider::class,
    ])
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
