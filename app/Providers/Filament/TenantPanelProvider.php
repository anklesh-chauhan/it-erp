<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Http\Middleware\SetAuthDefaults;
use App\Http\Middleware\IdentifyTenant;
use App\Filament\Resources\TenantUsers\TenantUserResource;
use Filament\Navigation\NavigationGroup;
use App\Filament\Tenant\Pages\Dashboard;
use Illuminate\Support\Facades\Route;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use App\Models\Tenant;
use App\Filament\Widgets\CurrentDatabase;

class TenantPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        FilamentAsset::register([
            Css::make('custom-css', asset('css/filament-custom.css')), // Custom CSS
            Js::make('sidebar-js', asset('js/sidebar.js')),             // Sidebar JavaScript
        ], true); // ✅ Use `isGlobal` to load for all panels

        return $panel
        ->id('tenant')
        ->path('tenant')
        ->brandName('Tenant CRM')
        ->login()
        ->globalSearch(true)
        ->globalSearchKeyBindings(['command + k', 'ctrl + k'])
        ->globalSearchDebounce(500) // ✅ Debounce search requests
        ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
        ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
        ->resources([
            TenantUserResource::class,
        ])
        ->widgets([
            CurrentDatabase::class,
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
        })
        ->topNavigation()
        ->renderHook('panels::body.start', function () {
            // Check if the current route is an authentication route
            $authRoutes = ['filament.admin.auth.login', 'filament.admin.auth.logout', 'filament.admin.auth.register', 'filament.admin.auth.password-reset.request', 'filament.admin.auth.password-reset.reset'];
            $isAuthPage = in_array(Route::currentRouteName(), $authRoutes);

            // On auth pages, render no sidebar
            if ($isAuthPage) {
                return '<div id="main-content" style="transition: margin-right 0.3s; margin-right: 0;">';
            }

            // Determine the current module based on the route
            $currentRoute = Route::currentRouteName();
            $currentPath = request()->path();

            // Default: no sidebar
            $sidebarView = null;
            $marginRight = '0';

            // City Pin Codes module (resource routes)
            if (str_contains($currentRoute, 'filament.admin.resources.city-pin-codes') || str_contains($currentPath, 'admin/city-pin-codes')) {
                $sidebarView = 'filament.sidebars.city-pin-codes-sidebar';
                $marginRight = '250px';
            }
            // Dashboard module (custom page or route)
            elseif (str_contains($currentRoute, 'filament.admin.pages.dashboard') || str_contains($currentPath, 'admin/dashboard')) {
                $sidebarView = 'filament.sidebars.dashboard-sidebar';
                $marginRight = '250px';
            }

            // Render the appropriate sidebar if defined, otherwise no sidebar
            if ($sidebarView) {
                return view($sidebarView) . '<div id="main-content" style="transition: margin-right 0.3s; margin-right: ' . $marginRight . ';">';
            }

            return '<div id="main-content" style="transition: margin-right 0.3s; margin-right: 0;">';
        })
        ->renderHook('panels::body.end', fn () => '</div>')
        ->plugins([
            FilamentShieldPlugin::make(),
        ]);
    }
}
