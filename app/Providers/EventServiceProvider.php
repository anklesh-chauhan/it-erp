<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\Company;
use App\Observers\CompanyObserver;
use App\Models\ContactDetail;
use App\Observers\ContactDetailObserver;
// use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
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
        ContactDetail::observe(ContactDetailObserver::class);
        Company::observe(CompanyObserver::class);
    }
}
