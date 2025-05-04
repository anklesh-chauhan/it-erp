<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Http\Middleware\SetAuthDefaults;
use App\Http\Middleware\IdentifyTenant;
use App\Filament\Resources\TenantUserResource;
use Filament\Navigation\NavigationGroup;
use App\Filament\Tenant\Pages\Dashboard;
use Illuminate\Support\Facades\Route;


class TenantPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
        ->id('tenant')
        ->path('tenant')
        ->brandName('Tenant CRM')
        ->login()
        ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
        ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
        ->resources([
            TenantUserResource::class,
        ])
        ->pages([
            Dashboard::class,
        ])
        ->sidebarCollapsibleOnDesktop()
        ->middleware([
            DispatchServingFilamentEvent::class,
            IdentifyTenant::class,
            'web',
        ])
        ->authGuard('tenant')
        ->navigationGroups([
            NavigationGroup::make()->label('Tenant Dashboard'),
        ])
        ->routes(function () {
            Route::middleware(['web'])->group(base_path('routes/tenant.php'));
        });
    }
}
