<?php

use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use App\Http\Controllers\SalesDocumentPdfController;

Route::middleware(['web'])->group(function () {
    Route::get('/dashboard', function () {
        return 'Tenant Dashboard';
    })->name('tenant.dashboard');

    Route::get('/sales-documents/{type}/{id}/preview', [SalesDocumentPdfController::class, 'preview'])
        ->name('sales-documents.preview');

    Route::get('/sales-documents/{type}/{id}/download', [SalesDocumentPdfController::class, 'download'])
        ->name('sales-documents.download');
});
