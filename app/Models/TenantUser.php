<?php

namespace App\Models;

use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Multitenancy\Models\Concerns\UsesTenantConnection;

use App\Traits\HasApprovalWorkflow;

class TenantUser extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, UsesTenantConnection, HasApprovalWorkflow;

    protected $connection = 'tenant';

    protected $table = 'users';

    protected $guard_name = 'tenant';

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return true; // Allow access to the Filament panel
    }


}

