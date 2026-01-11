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

    Route::get('/leave/email/approve', function () {
    abort_unless(request()->hasValidSignature(), 403);

    app(\App\Services\Attendance\LeaveWorkflowService::class)
        ->approveFromEmail(request('step'));

    return 'Leave approved successfully.';
    })->name('leave.email.approve');

    Route::get('/leave/email/reject', function () {
        abort_unless(request()->hasValidSignature(), 403);

        app(\App\Services\Attendance\LeaveWorkflowService::class)
            ->rejectFromEmail(request('step'));

        return 'Leave rejected successfully.';
    })->name('leave.email.reject');

});
