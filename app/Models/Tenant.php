<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Spatie\Multitenancy\Models\Tenant as BaseTenant;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Seeder;
use Database\Seeders\DatabaseSeeder;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use App\Jobs\ManageCloudflareCname;

class Tenant extends BaseTenant
{
    // use UsesTenantConnection;

    public function getDatabaseName(): string
    {
        return $this->database;
    }

    // public function getConnectionName(): ?string
    // {
    //     return 'tenant';
    // }

    protected $fillable = ['name', 'domain', 'database', 'cloudflare_record_id'];

    protected static function booted()
    {
        static::creating(function ($tenant) {
            $tenant->database = 'tenant_' . uniqid();
        });

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

                // Copy permissions from landlord database
                $mainPermissions = \Spatie\Permission\Models\Permission::get()->map(function ($permission) {
                    return [
                        'name' => $permission->name,
                        'guard_name' => 'tenant',
                        'created_at' => $permission->created_at ?: now(),
                        'updated_at' => $permission->updated_at ?: now(),
                    ];
                })->toArray();

                if (!empty($mainPermissions)) {
                    DB::connection('tenant')->table('permissions')->insertOrIgnore($mainPermissions);
                    Log::info("Inserted permissions for tenant: {$tenant->name}, count: " . count($mainPermissions));
                } else {
                    Log::warning("No permissions found in landlord database to copy for tenant: {$tenant->name}");
                }

                // ⬇️ New: Copy roles and their permissions
                $landlordRoles = \Spatie\Permission\Models\Role::with('permissions')->get();
                foreach ($landlordRoles as $role) {
                    // Create role in tenant DB
                    DB::connection('tenant')->table('roles')->insertOrIgnore([
                        'name' => $role->name,
                        'guard_name' => 'tenant',
                        'created_at' => $role->created_at ?: now(),
                        'updated_at' => $role->updated_at ?: now(),
                    ]);

                    // Attach permissions to roles
                    foreach ($role->permissions as $perm) {
                        // Get IDs from tenant DB
                        $permissionId = DB::connection('tenant')->table('permissions')
                            ->where('name', $perm->name)
                            ->value('id');

                        $roleId = DB::connection('tenant')->table('roles')
                            ->where('name', $role->name)
                            ->value('id');

                        if ($permissionId && $roleId) {
                            DB::connection('tenant')->table('role_has_permissions')->insertOrIgnore([
                                'permission_id' => $permissionId,
                                'role_id' => $roleId,
                            ]);
                        }
                    }
                }
                Log::info("Roles and their permissions synced for tenant: {$tenant->name}");

                 // ✅ Run tenant seeder programmatically (not Artisan)
                app('db')->setDefaultConnection('tenant');
                $seeder = new \Database\Seeders\DatabaseSeeder();
                $seeder->run();
                Log::info("Seeded tenant database for: {$tenant->name}");

                 // Restore landlord DB connection

                app('db')->setDefaultConnection(config('database.default'));

                // Dispatch job to create CNAME record in Cloudflare
                ManageCloudflareCname::dispatch($tenant, 'create');
                Log::info("Dispatched Cloudflare CNAME creation job for tenant: {$tenant->name}");

                // Switch back to the landlord context
                $tenant->forgetCurrent();
                Log::info("Tenant context cleared for: {$tenant->name}");


            } catch (\Exception $e) {
                Log::error("Error processing tenant {$tenant->name}: {$e->getMessage()}", ['exception' => $e->getTraceAsString()]);
                throw $e; // Re-throw to halt seeding if critical
            }
        });

        static::updating(function ($tenant) {
            if ($tenant->isDirty('domain')) {
                $oldDomain = $tenant->getOriginal('domain');
                $newDomain = $tenant->domain;
                Log::info("Tenant domain changing from {$oldDomain} to {$newDomain}");

                // Dispatch job to update CNAME record in Cloudflare
                ManageCloudflareCname::dispatch($tenant, 'update', $oldDomain);
                Log::info("Dispatched Cloudflare CNAME update job for tenant: {$tenant->name}");
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

                // Dispatch job to update CNAME record in Cloudflare
                ManageCloudflareCname::dispatch($tenant, 'update', $oldDomain);
                Log::info("Dispatched Cloudflare CNAME update job for tenant: {$tenant->name}");
            }
        });
        static::deleted(function ($tenant) {
            // Perform any additional cleanup if needed
            Log::info("Tenant {$tenant->name} deleted.");
            // Dispatch job to delete CNAME record in Cloudflare
            ManageCloudflareCname::dispatch($tenant, 'delete');
            Log::info("Dispatched Cloudflare CNAME deletion job for tenant: {$tenant->name}");
        });
    }
}
