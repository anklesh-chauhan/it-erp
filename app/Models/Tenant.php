<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Spatie\Multitenancy\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant
{
    protected $fillable = ['name', 'domain', 'database'];

    protected static function booted()
    {
        static::creating(function ($tenant) {
            $tenant->database = 'tenant_' . uniqid();
        });

        static::created(function ($tenant) {
            DB::statement("CREATE DATABASE {$tenant->database}");

            config(['database.connections.tenant.database' => $tenant->database]);

            Artisan::call('migrate', [
                '--database' => 'tenant',
                '--path' => 'database/migrations',
                '--force' => true,
            ]);
        });
    }
}
