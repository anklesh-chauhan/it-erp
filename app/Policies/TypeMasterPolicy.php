<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\TypeMaster;
use Illuminate\Auth\Access\HandlesAuthorization;

class TypeMasterPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:TypeMaster');
    }

    public function view(AuthUser $authUser, TypeMaster $typeMaster): bool
    {
        return $authUser->can('View:TypeMaster');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:TypeMaster');
    }

    public function update(AuthUser $authUser, TypeMaster $typeMaster): bool
    {
        return $authUser->can('Update:TypeMaster');
    }

    public function delete(AuthUser $authUser, TypeMaster $typeMaster): bool
    {
        return $authUser->can('Delete:TypeMaster');
    }

    public function restore(AuthUser $authUser, TypeMaster $typeMaster): bool
    {
        return $authUser->can('Restore:TypeMaster');
    }

    public function forceDelete(AuthUser $authUser, TypeMaster $typeMaster): bool
    {
        return $authUser->can('ForceDelete:TypeMaster');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:TypeMaster');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:TypeMaster');
    }

    public function replicate(AuthUser $authUser, TypeMaster $typeMaster): bool
    {
        return $authUser->can('Replicate:TypeMaster');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:TypeMaster');
    }

    public function viewOwnTerritory(AuthUser $authUser, TypeMaster $typeMaster): bool
    {
        return $authUser->can('ViewOwnTerritory:TypeMaster');
    }

    public function viewOwnOU(AuthUser $authUser, TypeMaster $typeMaster): bool
    {
        return $authUser->can('ViewOwnOU:TypeMaster');
    }

    public function viewOwn(AuthUser $authUser, TypeMaster $typeMaster): bool
    {
        return $authUser->can('ViewOwn:TypeMaster');
    }

}