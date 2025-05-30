<?php

namespace App\Tasks;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Spatie\Multitenancy\Concerns\UsesMultitenancyConfig;
use Spatie\Multitenancy\Contracts\IsTenant;
use Spatie\Multitenancy\Exceptions\InvalidConfiguration;
use Spatie\Multitenancy\Tasks\SwitchTenantTask;
use Illuminate\Support\Facades\Log;

class CustomSwitchTenantDatabaseTask implements SwitchTenantTask
{
    use UsesMultitenancyConfig;

    public function makeCurrent(IsTenant $tenant): void
    {
        Log::info("Switching to tenant database: {$tenant->database}");
        $this->setTenantConnectionDatabaseName($tenant->getDatabaseName());
        Log::info("Default database connection set to: " . DB::getDefaultConnection()); // Added log for confirmation
        Log::info("Database connection after switch: " . config('database.connections.tenant.database'));
    }

    public function forgetCurrent(): void
    {
        $this->setTenantConnectionDatabaseName(null);
    }

    protected function setTenantConnectionDatabaseName(?string $databaseName): void
    {
        $tenantConnectionName = $this->tenantDatabaseConnectionName();

        if ($tenantConnectionName === $this->landlordDatabaseConnectionName()) {
            throw InvalidConfiguration::tenantConnectionIsEmptyOrEqualsToLandlordConnection();
        }

        if (is_null(config("database.connections.{$tenantConnectionName}"))) {
            throw InvalidConfiguration::tenantConnectionDoesNotExist($tenantConnectionName);
        }

        config([
            "database.connections.{$tenantConnectionName}.database" => $databaseName,
        ]);

        app('db')->extend($tenantConnectionName, function ($config, $name) use ($databaseName) {
            $config['database'] = $databaseName;
            return app('db.factory')->make($config, $name);
        });

        DB::purge($tenantConnectionName);

        // Explicitly set the default connection to the tenant connection
        DB::setDefaultConnection($tenantConnectionName);

        // Octane will have an old `db` instance in the Model::$resolver.
        Model::setConnectionResolver(app('db'));
    }
}
