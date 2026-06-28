<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Multitenancy\Commands\TenantsArtisanCommand;
use App\Filament\Support\GlobalApprovalActionInjector;
use App\Events\ApprovalStatusChanged;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Tables\Table;
use Filament\Support\Facades\FilamentView;
use App\Database\Macros\BlueprintMacros;
use App\Listeners\ApprovalListener;
use App\Models\Media;
use App\Models\Visit;
use App\Observers\MediaObserver;
use App\Observers\VisitObserver;
use Illuminate\Support\Facades\Event;

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
        Event::listen(ApprovalStatusChanged::class, ApprovalListener::class);
        Media::observe(MediaObserver::class);
        Visit::observe(VisitObserver::class);
    }
}
