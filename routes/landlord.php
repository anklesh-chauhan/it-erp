<?php

use Illuminate\Support\Facades\Route;

Route::get('/landlord/dashboard', function () {
    return redirect('/admin'); // Redirect to Filament Panel
});
