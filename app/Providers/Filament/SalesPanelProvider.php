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


class SalesPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('sales')
            ->path('sales')
            ->globalSearch(true)
            ->topbar(false)
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Sales/Resources'), for: 'App\Filament\Sales\Resources')
            ->discoverPages(in: app_path('Filament/Sales/Pages'), for: 'App\Filament\Sales\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->resources([
                LeadResource::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Sales/Widgets'), for: 'App\Filament\Sales\Widgets')
            ->widgets([
                //
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
            ->plugins([
                // FilamentShieldPlugin::make(),
            ])
            ->sidebarCollapsibleOnDesktop()
            ->sidebarWidth('14rem')
            ->authGuard('web');
    }

    public function boot(): void
    {
        FilamentView::registerRenderHook(
            // This hook places content at the start of the user menu (top right)
            PanelsRenderHook::GLOBAL_SEARCH_AFTER,
            fn (): string => Blade::render('@livewire(\'app.filament.widgets.check-in-widget\')'),
        );
    }
}
