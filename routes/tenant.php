<?php

use Illuminate\Support\Facades\Route;
use Filament\Pages\Auth\Login;
use App\Models\Tenant;

// Ensure CSRF protection
// Route::middleware(['web', 'auth:tenant'])->group(function () {
//     Route::get('/dashboard', function () {
//         return view('dashboard');
//     })->name('dashboard');
// });


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
// Login route
Route::get('/tenant/login', Login::class)->name('filament.tenant.auth.login');
