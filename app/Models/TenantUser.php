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

    public function getTenants()
    {
        return $this->belongsTo(Tenant::class);
    }
    public function getTenantId()
    {
        return $this->getTenants()->first()->id;
    }
    public function getTenantName()
    {
        return $this->getTenants()->first()->name;
    }
    public function getTenantDomain()
    {
        return $this->getTenants()->first()->domain;
    }
    public function getTenantDatabase()
    {
        return $this->getTenants()->first()->database;
    }

}

