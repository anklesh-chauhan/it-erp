<?php

use Illuminate\Support\Facades\Route;
use Filament\Pages\Auth\Login;

// Ensure CSRF protection
Route::middleware(['web', 'auth:tenant'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Login route
Route::get('/tenant/login', Login::class)->name('filament.tenant.auth.login');
