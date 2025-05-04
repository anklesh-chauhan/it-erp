<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Multitenancy\Models\Tenant;

class IdentifyTenant
{
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost(); // e.g., tenant1.crm.local

        // Skip tenant identification for the landlord domain (crm.local)
        if ($host === 'crm.local') {
            return $next($request);
        }

        // Find the tenant by domain
        $tenant = Tenant::where('domain', $host)->first();

        if (! $tenant) {
            abort(404, 'Tenant not found');
        }

        // Make the tenant the current tenant
        $tenant->makeCurrent();

        return $next($request);
    }
}
