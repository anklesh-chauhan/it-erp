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

    // protected $table = 'users';

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

    // public function tenants()
    // {
    //     return $this->belongsToMany(Tenant::class, 'tenant_user');
    // }

    // public function getTenants(): array
    // {
    //     return $this->tenants()->get()->all(); // returns array for Filament
    // }

}

