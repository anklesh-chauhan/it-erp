<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

class IdentifyTenant
{
    public function handle(Request $request, Closure $next)
    {
        $host = $request->getHost();
        \Illuminate\Support\Facades\Log::info("Processing host: {$host}");

        if ($host === 'iterp.in') {
            \Illuminate\Support\Facades\Log::info('Skipping tenant identification for landlord domain: iterp.in');
            DB::setDefaultConnection('mysql');
            return $next($request);
        }

        \Illuminate\Support\Facades\Log::info("Querying tenants table on mysql connection");
        $tenant = Tenant::on('mysql')->where('domain', $host)->first();
        \Illuminate\Support\Facades\Log::info("Tenant query executed", [
            'host' => $host,
            'tenant' => $tenant ? $tenant->toArray() : null,
        ]);

        if ($tenant) {
            \Illuminate\Support\Facades\Log::info("Tenant identified: {$tenant->id} - {$host}", [
                'tenantId' => $tenant->id,
                'database' => $tenant->database,
            ]);
            config(['database.connections.tenant.database' => $tenant->database]);
            DB::purge('tenant');
            DB::setDefaultConnection('tenant');
            \Illuminate\Support\Facades\Log::info("Switching to tenant database: {$tenant->database}");
            // Set tenant as current for Spatie Multitenancy
            $tenant->makeCurrent();
        } else {
            \Illuminate\Support\Facades\Log::error("No tenant found for domain: {$host}");
            abort(404, 'Tenant not found');
        }

        return $next($request);
    }
}
