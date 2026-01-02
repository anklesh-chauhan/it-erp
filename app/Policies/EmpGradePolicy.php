<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\EmpGrade;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmpGradePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:EmpGrade');
    }

    public function view(AuthUser $authUser, EmpGrade $empGrade): bool
    {
        return $authUser->can('View:EmpGrade');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:EmpGrade');
    }

    public function update(AuthUser $authUser, EmpGrade $empGrade): bool
    {
        return $authUser->can('Update:EmpGrade');
    }

    public function delete(AuthUser $authUser, EmpGrade $empGrade): bool
    {
        return $authUser->can('Delete:EmpGrade');
    }

    public function restore(AuthUser $authUser, EmpGrade $empGrade): bool
    {
        return $authUser->can('Restore:EmpGrade');
    }

    public function forceDelete(AuthUser $authUser, EmpGrade $empGrade): bool
    {
        return $authUser->can('ForceDelete:EmpGrade');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:EmpGrade');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:EmpGrade');
    }

    public function replicate(AuthUser $authUser, EmpGrade $empGrade): bool
    {
        return $authUser->can('Replicate:EmpGrade');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:EmpGrade');
    }

    public function viewOwnTerritory(AuthUser $authUser, EmpGrade $empGrade): bool
    {
        return $authUser->can('ViewOwnTerritory:EmpGrade');
    }

    public function viewOwnOU(AuthUser $authUser, EmpGrade $empGrade): bool
    {
        return $authUser->can('ViewOwnOU:EmpGrade');
    }

    public function viewOwn(AuthUser $authUser, EmpGrade $empGrade): bool
    {
        return $authUser->can('ViewOwn:EmpGrade');
    }

}