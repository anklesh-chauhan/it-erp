<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Spatie\Multitenancy\Models\Tenant as BaseTenant;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Seeder;
use Database\Seeders\DatabaseSeeder;

class Tenant extends BaseTenant
{
    protected $fillable = ['name', 'domain', 'database'];

    protected static function booted()
    {
        static::creating(function ($tenant) {
            $tenant->database = 'tenant_' . uniqid();
        });

        // static::created(function ($tenant) {
        //     DB::statement("CREATE DATABASE {$tenant->database}");

        //     config(['database.connections.tenant.database' => $tenant->database]);

        //     Artisan::call('migrate', [
        //         '--database' => 'tenant',
        //         '--path' => 'database/migrations',
        //         '--force' => true,
        //     ]);
        // });

        static::created(function ($tenant) {
            Log::info("Tenant created: {$tenant->name}, database: {$tenant->database}");

            try {
                // Create the tenant database
                DB::statement("CREATE DATABASE {$tenant->database}");
                Log::info("Database created: {$tenant->database}");

                // Set the tenant database connection
                config(['database.connections.tenant.database' => $tenant->database]);
                DB::purge('tenant'); // Clear connection cache
                Log::info("Tenant connection set to: {$tenant->database}");

                // Run migrations for the tenant database
                Artisan::call('migrate', [
                    '--database' => 'tenant',
                    '--path' => 'database/migrations',
                    '--force' => true,
                ]);
                Log::info("Migrations run for tenant: {$tenant->name}");

                // Verify tenant database connection
                $tenantDb = DB::connection('tenant')->getDatabaseName();
                Log::info("Current tenant database: {$tenantDb}");

                // Switch to the tenant context
                $tenant->makeCurrent();
                Log::info("Tenant context set for: {$tenant->name}", ['tenantId' => $tenant->id]);

                // Verify permissions table exists
                $tableExists = DB::connection('tenant')->getSchemaBuilder()->hasTable('permissions');
                Log::info("Permissions table exists in tenant database: " . ($tableExists ? 'Yes' : 'No'));

                // Copy permissions from the current (landlord) database
                $mainPermissions = Permission::get()->map(function ($permission) {
                    return [
                        'name' => $permission->name,
                        'guard_name' => 'tenant',
                        'created_at' => $permission->created_at ?: now(),
                        'updated_at' => $permission->updated_at ?: now(),
                    ];
                })->toArray();

                Log::info("Fetched permissions from landlord database");

                // Insert permissions into the tenant database using bulk insert
                if (!empty($mainPermissions)) {
                    DB::connection('tenant')->table('permissions')->insertOrIgnore($mainPermissions);
                    Log::info("Inserted permissions for tenant: {$tenant->name}, count: " . count($mainPermissions));
                } else {
                    Log::warning("No permissions found in landlord database to copy for tenant: {$tenant->name}");
                }

                // Verify inserted permissions
                $insertedCount = DB::connection('tenant')->table('permissions')->count();
                Log::info("Total permissions in tenant database after insert: {$insertedCount}");

                 // ✅ Run tenant seeder programmatically (not Artisan)
                 app('db')->setDefaultConnection('tenant');
                 app(Seeder::class)->call(DatabaseSeeder::class);
                 Log::info("Seeded tenant database for: {$tenant->name}");

                 // Restore landlord DB connection

                 app('db')->setDefaultConnection(config('database.default'));

                // Switch back to the landlord context
                $tenant->forgetCurrent();
                Log::info("Tenant context cleared for: {$tenant->name}");
            } catch (\Exception $e) {
                Log::error("Error processing tenant {$tenant->name}: {$e->getMessage()}", ['exception' => $e->getTraceAsString()]);
                throw $e; // Re-throw to halt seeding if critical
            }
        });

        static::deleting(function ($tenant) {
            DB::statement("DROP DATABASE {$tenant->database}");
        });
        static::updated(function ($tenant) {
            if ($tenant->isDirty('domain')) {
                $oldDomain = $tenant->getOriginal('domain');
                $newDomain = $tenant->domain;

                // Update the domain in the database
                DB::table('tenants')->where('id', $tenant->id)->update(['domain' => $newDomain]);

                // Log the domain change
                Log::info("Tenant domain changed from {$oldDomain} to {$newDomain}");
            }
        });
        static::deleted(function ($tenant) {
            // Perform any additional cleanup if needed
            Log::info("Tenant {$tenant->name} deleted.");
        });
    }
}
