<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Assets\Css;
use App\Filament\Resources\LeadResource;

class FilamentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        FilamentAsset::register([
            Css::make('filament-custom-styles', asset('css/filament-custom.css')),
        ], true);

        // Optionally register custom icons
        FilamentIcon::register([
            'custom-icon' => '<svg>...</svg>',
        ]);
    }
}
