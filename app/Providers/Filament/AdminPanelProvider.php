<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
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
use App\Filament\Pages\Dashboard;
use App\Filament\Resources\Users\UserResource;
use App\Filament\Resources\Roles\RoleResource;
use App\Filament\Resources\Permissions\PermissionResource;
use App\Providers\Filament\GlobalSearch\ResourceShortcutSearch;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use App\Filament\Resources\GlobalSearchResource;
use App\Filament\Resources\Leads\LeadResource;
use App\Models\Lead;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Actions\Action;
use App\Filament\Resources\Organizations\OrganizationResource;
use Illuminate\Support\Facades\Blade;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use App\Http\Middleware\RedirectMarketingToPunchIn;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        FilamentAsset::register([
            Css::make('custom-css', asset('css/filament-custom.css')), // Custom CSS
            // Js::make('sidebar-js', asset('js/sidebar.js')),             // Sidebar JavaScript
        ], true); // ✅ Use `isGlobal` to load for all panels

        return $panel
            ->default()
            ->globalSearch(true)
            ->topbar(false)
            ->globalSearchKeyBindings(['command + k', 'ctrl + k'])
            ->globalSearchDebounce(500) // ✅ Debounce search requests
            ->id('admin')
            ->path('admin')
            ->login()
            ->profile()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->userMenuItems([
                'organization-profile' => Action::make('organization-profile')
                    ->label('Organization Profile')
                    // ⬇️ FIX: Wrap the getUrl() call in an arrow function (fn())
                    // This ensures it only runs after the application and routes are loaded.
                    ->url(fn (): string => OrganizationResource::getUrl('edit', ['record' => 1]))
                    ->icon('heroicon-o-building-office')
                    ->visible(fn () => OrganizationResource::canViewAny()),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->resources([
                //
            ])
            ->plugins([
                FilamentShieldPlugin::make(),
            ])
            ->widgets([
                // Widgets displayed in the dashboard
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
                RedirectMarketingToPunchIn::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->sidebarCollapsibleOnDesktop()
            ->sidebarWidth('14rem')
            ->authGuard('web');
    }


}
