<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Multitenancy\Models\Tenant;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class IdentifyTenant
{
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost();
        Log::info("Request Host: {$host}");

        if ($host === 'crm.local') {
            Log::info("Skipping tenant identification for landlord domain.");
            return $next($request);
        }

        $tenant = Tenant::where('domain', $host)->first();

        if (! $tenant) {
            Log::error("Tenant not found for domain: {$host}");
            abort(404, 'Tenant not found');
        }

        Log::info("Tenant identified: {$tenant->id} - {$tenant->domain}");
        $tenant->makeCurrent();
        Log::info("Current database connection1:" . DB::getDefaultConnection());

        return $next($request);
    }
}
