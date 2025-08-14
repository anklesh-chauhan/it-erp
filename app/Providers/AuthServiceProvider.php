<?php

namespace App\Providers;

use App\Models\Lead;
use App\Policies\LeadPolicy;
use App\Models\Deal;
use App\Policies\DealPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Lead::class => LeadPolicy::class,
        Deal::class => DealPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
