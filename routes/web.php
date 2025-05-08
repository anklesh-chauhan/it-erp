<?php

use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

Route::middleware(['web'])->group(function () {
    Route::get('/dashboard', function () {
        return 'Tenant Dashboard';
    })->name('tenant.dashboard');
});
