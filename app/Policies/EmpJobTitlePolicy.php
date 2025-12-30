<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\EmpJobTitle;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmpJobTitlePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:EmpJobTitle');
    }

    public function view(AuthUser $authUser, EmpJobTitle $empJobTitle): bool
    {
        return $authUser->can('View:EmpJobTitle');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:EmpJobTitle');
    }

    public function update(AuthUser $authUser, EmpJobTitle $empJobTitle): bool
    {
        return $authUser->can('Update:EmpJobTitle');
    }

    public function delete(AuthUser $authUser, EmpJobTitle $empJobTitle): bool
    {
        return $authUser->can('Delete:EmpJobTitle');
    }

    public function restore(AuthUser $authUser, EmpJobTitle $empJobTitle): bool
    {
        return $authUser->can('Restore:EmpJobTitle');
    }

    public function forceDelete(AuthUser $authUser, EmpJobTitle $empJobTitle): bool
    {
        return $authUser->can('ForceDelete:EmpJobTitle');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:EmpJobTitle');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:EmpJobTitle');
    }

    public function replicate(AuthUser $authUser, EmpJobTitle $empJobTitle): bool
    {
        return $authUser->can('Replicate:EmpJobTitle');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:EmpJobTitle');
    }

    public function viewOwnTerritory(AuthUser $authUser, EmpJobTitle $empJobTitle): bool
    {
        return $authUser->can('ViewOwnTerritory:EmpJobTitle');
    }

    public function viewOwn(AuthUser $authUser, EmpJobTitle $empJobTitle): bool
    {
        return $authUser->can('ViewOwn:EmpJobTitle');
    }

}