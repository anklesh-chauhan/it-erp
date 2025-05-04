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
use Filament\Http\Middleware\SetAuthDefaults;
use Filament\Http\Middleware\RedirectIfAuthenticated;
use App\Filament\Landlord\Pages\Dashboard;

class LandlordPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('landlord')
            ->path('landlord')
            ->brandName('Landlord CRM')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Landlord/Resources'), for: 'App\\Filament\\Landlord\\Resources')
            ->discoverPages(in: app_path('Filament/Landlord/Pages'), for: 'App\\Filament\\Landlord\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Landlord/Widgets'), for: 'App\\Filament\\Landlord\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->login()
            ->sidebarCollapsibleOnDesktop()
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                Authenticate::class,
                DispatchServingFilamentEvent::class,
                // SetAuthDefaults::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
            // ->resources([
            //     UserResource::class, // Example Filament resource
            // ])
            // ->navigationGroups([
            //     NavigationGroup::make()->label('Management'),
            // ]);
    }
}
