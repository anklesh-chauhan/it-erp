<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\EmpDepartment;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmpDepartmentPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:EmpDepartment');
    }

    public function view(AuthUser $authUser, EmpDepartment $empDepartment): bool
    {
        return $authUser->can('View:EmpDepartment');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:EmpDepartment');
    }

    public function update(AuthUser $authUser, EmpDepartment $empDepartment): bool
    {
        return $authUser->can('Update:EmpDepartment');
    }

    public function delete(AuthUser $authUser, EmpDepartment $empDepartment): bool
    {
        return $authUser->can('Delete:EmpDepartment');
    }

    public function restore(AuthUser $authUser, EmpDepartment $empDepartment): bool
    {
        return $authUser->can('Restore:EmpDepartment');
    }

    public function forceDelete(AuthUser $authUser, EmpDepartment $empDepartment): bool
    {
        return $authUser->can('ForceDelete:EmpDepartment');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:EmpDepartment');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:EmpDepartment');
    }

    public function replicate(AuthUser $authUser, EmpDepartment $empDepartment): bool
    {
        return $authUser->can('Replicate:EmpDepartment');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:EmpDepartment');
    }

}