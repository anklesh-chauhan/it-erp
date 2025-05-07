<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Spatie\Multitenancy\Models\Tenant as BaseTenant;
use Spatie\Permission\Models\Permission;

class Tenant extends BaseTenant
{
    protected $fillable = ['name', 'domain', 'database'];

    protected static function booted()
    {
        static::creating(function ($tenant) {
            $tenant->database = 'tenant_' . uniqid();
        });

        static::created(function ($tenant) {
            Log::info("Creating tenant: {$tenant->name}");

            try {
                // 1. Create tenant database
                DB::statement("CREATE DATABASE `{$tenant->database}`");
                Log::info("Created database: {$tenant->database}");

                // 2. Configure tenant DB connection
                config(['database.connections.tenant.database' => $tenant->database]);
                DB::purge('tenant');
                DB::reconnect('tenant');
                Log::info("Tenant DB connection configured: {$tenant->database}");

                // 3. Run tenant migrations
                Artisan::call('migrate', [
                    '--database' => 'tenant',
                    '--path' => 'database/migrations',
                    '--force' => true,
                ]);
                Log::info("Migrations completed for tenant: {$tenant->name}");

                // 4. Set tenant context
                $tenant->makeCurrent();

                // 5. Clone permissions from landlord DB
                $landlordPermissions = Permission::on(config('database.default'))->get()->map(function ($permission) {
                    return [
                        'name' => $permission->name,
                        'guard_name' => 'tenant',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                })->toArray();

                if (!empty($landlordPermissions)) {
                    DB::connection('tenant')->table('permissions')->insertOrIgnore($landlordPermissions);
                    Log::info("Permissions copied: " . count($landlordPermissions));
                } else {
                    Log::warning("No permissions found in landlord DB.");
                }

                // 6. Run tenant-specific seeder (if needed)
                Artisan::call('tenants:artisan', [
                    'artisanCommand' => 'db:seed --class=DatabaseSeeder',
                    '--tenant' => [$tenant->id],
                ]);
                Log::info("Seeder executed for tenant: {$tenant->name}");

                // 7. Cleanup
                $tenant->forgetCurrent();
                Log::info("Tenant context cleared: {$tenant->name}");
            } catch (\Throwable $e) {
                Log::error("Error creating tenant {$tenant->name}: {$e->getMessage()}", [
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }
        });

        static::deleting(function ($tenant) {
            DB::statement("DROP DATABASE IF EXISTS `{$tenant->database}`");
            Log::info("Dropped database: {$tenant->database}");
        });

        static::updated(function ($tenant) {
            if ($tenant->isDirty('domain')) {
                $oldDomain = $tenant->getOriginal('domain');
                Log::info("Domain changed from {$oldDomain} to {$tenant->domain}");
            }
        });

        static::deleted(function ($tenant) {
            Log::info("Tenant deleted: {$tenant->name}");
        });
    }
}
