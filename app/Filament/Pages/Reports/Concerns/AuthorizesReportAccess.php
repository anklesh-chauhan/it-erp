<?php

namespace App\Filament\Pages\Reports\Concerns;

use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

trait AuthorizesReportAccess
{
    public static function canAccess(): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        if (
            $user->hasRole('super_admin') ||
            $user->hasRole('administration_admin') ||
            $user->can('AccessAllRecords')
        ) {
            return true;
        }

        $permission = 'View:'.class_basename(static::class);
        $guard = $user->getGuardName();

        $permissionExists = Permission::query()
            ->where('name', $permission)
            ->where('guard_name', $guard)
            ->exists();

        if (! $permissionExists) {
            return true;
        }

        return $user->can($permission);
    }
}
