<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Filament\Admin\Pages\Dashboard;
use App\Filament\Resources\UserResource;
use App\Filament\Resources\RoleResource;
use App\Filament\Resources\PermissionResource;
use App\Providers\Filament\GlobalSearch\ResourceShortcutSearch;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use App\Filament\Resources\GlobalSearchResource;
use App\Filament\Resources\LeadResource;
use App\Models\Lead;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        FilamentAsset::register([
            Css::make('custom-css', asset('css/filament-custom.css')), // Custom CSS
            Js::make('sidebar-js', asset('js/sidebar.js')),             // Sidebar JavaScript
        ], true); // ✅ Use `isGlobal` to load for all panels

        return $panel
            ->default()
            ->globalSearch(true)
            ->globalSearchKeyBindings(['command + k', 'ctrl + k'])
            ->globalSearchDebounce(500) // ✅ Debounce search requests
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->resources([
                LeadResource::class,
                UserResource::class,
                RoleResource::class,
                PermissionResource::class,
            ])
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            // ->sidebarCollapsibleOnDesktop()
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
