<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Spatie\Permission\Traits\HasRoles;

class TenantUser extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasRoles;

    protected $connection = 'tenant';

    protected $table = 'users';

    protected $guarded = [];

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        return true; // Allow access to the Filament panel
    }

    public function guardName()
    {
        return 'tenant';
    }

    /**
     * Get the tenants accessible by this user (required by Filament).
     */
    public function getTenants(\Filament\Panel $panel): \Illuminate\Support\Collection
    {
        // Return the current tenant for this user
        $tenant = Tenant::current();
        return $tenant ? collect([$tenant]) : collect();
    }

    /**
     * Determine if the user can access the given tenant.
     */
    public function canAccessTenant($tenant): bool
    {
        // Check if the tenant matches the current tenant
        $currentTenant = Tenant::current();
        return $currentTenant && $currentTenant->id === ($tenant->id ?? $tenant);
    }

}

