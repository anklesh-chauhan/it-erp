<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Filament\Panel;
use App\Models\OrganizationalUnit;
use Filament\Models\Contracts\FilamentUser;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'employee_id',
        'organizational_unit_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getConnectionName()
    {
        return \Spatie\Multitenancy\Models\Tenant::current() ? 'tenant' : 'mysql';
    }

    protected $guarded = [];

    public function getGuardName()
    {
        return \Spatie\Multitenancy\Models\Tenant::current() ? 'tenant' : 'web';
    }

    public function organizationalUnit(): BelongsTo
    {
        return $this->belongsTo(OrganizationalUnit::class);
    }

    public function employee()
    {
        return $this->hasOne(Employee::class, 'login_id');
    }

    public function employeeViaId()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function createdEmployees()
    {
        return $this->hasMany(Employee::class, 'created_by_user_id');
    }

    public function updatedEmployees()
    {
        return $this->hasMany(Employee::class, 'updated_by_user_id');
    }

    public function deletedEmployees()
    {
        return $this->hasMany(Employee::class, 'deleted_by_user_id');
    }

    public function createdDepartments()
    {
        return $this->hasMany(EmpDeparment::class, 'created_by_user_id');
    }

    public function updatedDepartments()
    {
        return $this->hasMany(EmpDeparment::class, 'updated_by_user_id');
    }

    public function deletedDepartments()
    {
        return $this->hasMany(EmpDeparment::class, 'deleted_by_user_id');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // Allow access if user is linked to an active, non-deleted employee via employee_id
        return $this->employeeViaId && $this->employeeViaId->is_active && !$this->employeeViaId->is_deleted;
    }

}
