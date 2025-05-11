<?php
namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Spatie\Multitenancy\Models\Tenant as BaseTenant;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Log;
use Database\Seeders\DatabaseSeeder;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;
use App\Jobs\ManageCloudflareCname;

class Tenant extends BaseTenant
{
    use UsesTenantConnection;

    protected $fillable = ['name', 'domain', 'database', 'cloudflare_record_id'];

    public function getDatabaseName(): string
    {
        return $this->database;
    }

    public function getConnectionName(): ?string
    {
        return 'tenant';
    }

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('landlord', function ($builder) {
            if (! \Spatie\Multitenancy\Models\Tenant::current()) {
                $builder->getQuery()->connection = DB::connection('mysql')->getName();
            }
        });
    }

    protected static function booted()
    {
        static::creating(function ($tenant) {
            $tenant->database = 'tenant_' . uniqid();
        });

        static::created(function ($tenant) {
            Log::info("Tenant created: {$tenant->name}, database: {$tenant->database}");

            try {
                // Create the tenant database
                DB::connection('mysql')->statement("CREATE DATABASE {$tenant->database}");
                Log::info("Database created: {$tenant->database}");

                // Set the tenant database connection
                config(['database.connections.tenant.database' => $tenant->database]);
                DB::purge('tenant');
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

                // Copy permissions from the landlord database
                $mainPermissions = Permission::on('mysql')->get()->map(function ($permission) {
                    return [
                        'name' => $permission->name,
                        'guard_name' => 'tenant',
                        'created_at' => $permission->created_at ?: now(),
                        'updated_at' => $permission->updated_at ?: now(),
                    ];
                })->toArray();

                Log::info("Fetched permissions from landlord database");

                if (!empty($mainPermissions)) {
                    DB::connection('tenant')->table('permissions')->insertOrIgnore($mainPermissions);
                    Log::info("Inserted permissions for tenant: {$tenant->name}, count: " . count($mainPermissions));
                } else {
                    Log::warning("No permissions found in landlord database to copy for tenant: {$tenant->name}");
                }

                // Verify inserted permissions
                $insertedCount = DB::connection('tenant')->table('permissions')->count();
                Log::info("Total permissions in tenant database after insert: {$insertedCount}");

                // Run tenant seeder
                app('db')->setDefaultConnection('tenant');
                $seeder = new \Database\Seeders\DatabaseSeeder();
                $seeder->run();
                Log::info("Seeded tenant database for: {$tenant->name}");

                // Restore landlord DB connection
                app('db')->setDefaultConnection(config('database.default'));

                // Switch back to the landlord context
                $tenant->forgetCurrent();
                Log::info("Tenant context cleared for: {$tenant->name}");

                // Dispatch Cloudflare job
                ManageCloudflareCname::dispatch($tenant, 'create');
                Log::info("Dispatched Cloudflare CNAME creation job for tenant: {$tenant->name}");
            } catch (\Exception $e) {
                Log::error("Error processing tenant {$tenant->name}: {$e->getMessage()}", ['exception' => $e->getTraceAsString()]);
                throw $e;
            }
        });

        static::updating(function ($tenant) {
            if ($tenant->isDirty('domain')) {
                $oldDomain = $tenant->getOriginal('domain');
                $newDomain = $tenant->domain;
                Log::info("Tenant domain changing from {$oldDomain} to {$newDomain}");
                ManageCloudflareCname::dispatch($tenant, 'update', $oldDomain);
                Log::info("Dispatched Cloudflare CNAME update job for tenant: {$tenant->name}");
            }
        });

        static::deleting(function ($tenant) {
            DB::connection('mysql')->statement("DROP DATABASE {$tenant->database}");
            Log::info("Tenant database dropped: {$tenant->database}");
        });

        static::updated(function ($tenant) {
            if ($tenant->isDirty('domain')) {
                $oldDomain = $tenant->getOriginal('domain');
                $newDomain = $tenant->domain;
                Log::info("Tenant domain changed from {$oldDomain} to {$newDomain}");
                ManageCloudflareCname::dispatch($tenant, 'update', $oldDomain);
                Log::info("Dispatched Cloudflare CNAME update job for tenant: {$tenant->name}");
            }
        });

        static::deleted(function ($tenant) {
            Log::info("Tenant {$tenant->name} deleted.");
            ManageCloudflareCname::dispatch($tenant, 'delete');
            Log::info("Dispatched Cloudflare CNAME deletion job for tenant: {$tenant->name}");
        });
    }
}
