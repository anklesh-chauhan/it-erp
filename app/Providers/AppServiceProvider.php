<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Multitenancy\Commands\TenantsArtisanCommand;
use App\Filament\Support\GlobalApprovalActionInjector;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Tables\Table;
use Filament\Support\Facades\FilamentView;
use App\Database\Macros\BlueprintMacros;
use App\Models\Approval;
use App\Observers\ApprovalObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->commands([
            TenantsArtisanCommand::class,
        ]);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        BlueprintMacros::register();
        Approval::observe(ApprovalObserver::class);
    }
}
