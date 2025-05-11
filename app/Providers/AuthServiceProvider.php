<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        \App\Models\Lead::class => \App\Policies\LeadPolicy::class,
        \App\Models\Deal::class => \App\Policies\DealPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
