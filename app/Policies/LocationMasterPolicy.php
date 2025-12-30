<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\LocationMaster;
use Illuminate\Auth\Access\HandlesAuthorization;

class LocationMasterPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:LocationMaster');
    }

    public function view(AuthUser $authUser, LocationMaster $locationMaster): bool
    {
        return $authUser->can('View:LocationMaster');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:LocationMaster');
    }

    public function update(AuthUser $authUser, LocationMaster $locationMaster): bool
    {
        return $authUser->can('Update:LocationMaster');
    }

    public function delete(AuthUser $authUser, LocationMaster $locationMaster): bool
    {
        return $authUser->can('Delete:LocationMaster');
    }

    public function restore(AuthUser $authUser, LocationMaster $locationMaster): bool
    {
        return $authUser->can('Restore:LocationMaster');
    }

    public function forceDelete(AuthUser $authUser, LocationMaster $locationMaster): bool
    {
        return $authUser->can('ForceDelete:LocationMaster');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:LocationMaster');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:LocationMaster');
    }

    public function replicate(AuthUser $authUser, LocationMaster $locationMaster): bool
    {
        return $authUser->can('Replicate:LocationMaster');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:LocationMaster');
    }

    public function viewOwnTerritory(AuthUser $authUser, LocationMaster $locationMaster): bool
    {
        return $authUser->can('ViewOwnTerritory:LocationMaster');
    }

    public function viewOwn(AuthUser $authUser, LocationMaster $locationMaster): bool
    {
        return $authUser->can('ViewOwn:LocationMaster');
    }

}