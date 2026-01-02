<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\EmployeeAttendanceStatus;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeeAttendanceStatusPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:EmployeeAttendanceStatus');
    }

    public function view(AuthUser $authUser, EmployeeAttendanceStatus $employeeAttendanceStatus): bool
    {
        return $authUser->can('View:EmployeeAttendanceStatus');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:EmployeeAttendanceStatus');
    }

    public function update(AuthUser $authUser, EmployeeAttendanceStatus $employeeAttendanceStatus): bool
    {
        return $authUser->can('Update:EmployeeAttendanceStatus');
    }

    public function delete(AuthUser $authUser, EmployeeAttendanceStatus $employeeAttendanceStatus): bool
    {
        return $authUser->can('Delete:EmployeeAttendanceStatus');
    }

    public function restore(AuthUser $authUser, EmployeeAttendanceStatus $employeeAttendanceStatus): bool
    {
        return $authUser->can('Restore:EmployeeAttendanceStatus');
    }

    public function forceDelete(AuthUser $authUser, EmployeeAttendanceStatus $employeeAttendanceStatus): bool
    {
        return $authUser->can('ForceDelete:EmployeeAttendanceStatus');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:EmployeeAttendanceStatus');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:EmployeeAttendanceStatus');
    }

    public function replicate(AuthUser $authUser, EmployeeAttendanceStatus $employeeAttendanceStatus): bool
    {
        return $authUser->can('Replicate:EmployeeAttendanceStatus');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:EmployeeAttendanceStatus');
    }

    public function viewOwnTerritory(AuthUser $authUser, EmployeeAttendanceStatus $employeeAttendanceStatus): bool
    {
        return $authUser->can('ViewOwnTerritory:EmployeeAttendanceStatus');
    }

    public function viewOwnOU(AuthUser $authUser, EmployeeAttendanceStatus $employeeAttendanceStatus): bool
    {
        return $authUser->can('ViewOwnOU:EmployeeAttendanceStatus');
    }

    public function viewOwn(AuthUser $authUser, EmployeeAttendanceStatus $employeeAttendanceStatus): bool
    {
        return $authUser->can('ViewOwn:EmployeeAttendanceStatus');
    }

}