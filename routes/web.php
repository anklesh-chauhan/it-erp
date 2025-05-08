<?php

use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use App\Models\Tenant;

Route::middleware(['web', 'auth:tenant'])->group(function () {
    Route::get('/dashboard', function () {
        $tenant = Tenant::where('id', \Spatie\Multitenancy\Models\Tenant::current()?->id)->first();
        if (!$tenant) {
            return redirect('/tenant/login'); // Redirect to login if no tenant
        }
        return redirect()->route('filament.tenant.pages.dashboard', ['tenant' => $tenant->domain]);
    })->name('dashboard');

    Route::post('/logout', function () {
        auth('tenant')->logout();
        return redirect('/tenant/login');
    })->name('logout');
});

Route::middleware(['web'])->group(function () {
    Route::get('/dashboard', function () {
        return 'Tenant Dashboard';
    })->name('tenant.dashboard');
});
