<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\EmpDivision;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmpDivisionPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:EmpDivision');
    }

    public function view(AuthUser $authUser, EmpDivision $empDivision): bool
    {
        return $authUser->can('View:EmpDivision');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:EmpDivision');
    }

    public function update(AuthUser $authUser, EmpDivision $empDivision): bool
    {
        return $authUser->can('Update:EmpDivision');
    }

    public function delete(AuthUser $authUser, EmpDivision $empDivision): bool
    {
        return $authUser->can('Delete:EmpDivision');
    }

    public function restore(AuthUser $authUser, EmpDivision $empDivision): bool
    {
        return $authUser->can('Restore:EmpDivision');
    }

    public function forceDelete(AuthUser $authUser, EmpDivision $empDivision): bool
    {
        return $authUser->can('ForceDelete:EmpDivision');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:EmpDivision');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:EmpDivision');
    }

    public function replicate(AuthUser $authUser, EmpDivision $empDivision): bool
    {
        return $authUser->can('Replicate:EmpDivision');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:EmpDivision');
    }

}