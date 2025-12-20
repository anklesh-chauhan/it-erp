<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ShiftMaster;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShiftMasterPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ShiftMaster');
    }

    public function view(AuthUser $authUser, ShiftMaster $shiftMaster): bool
    {
        return $authUser->can('View:ShiftMaster');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ShiftMaster');
    }

    public function update(AuthUser $authUser, ShiftMaster $shiftMaster): bool
    {
        return $authUser->can('Update:ShiftMaster');
    }

    public function delete(AuthUser $authUser, ShiftMaster $shiftMaster): bool
    {
        return $authUser->can('Delete:ShiftMaster');
    }

    public function restore(AuthUser $authUser, ShiftMaster $shiftMaster): bool
    {
        return $authUser->can('Restore:ShiftMaster');
    }

    public function forceDelete(AuthUser $authUser, ShiftMaster $shiftMaster): bool
    {
        return $authUser->can('ForceDelete:ShiftMaster');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ShiftMaster');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ShiftMaster');
    }

    public function replicate(AuthUser $authUser, ShiftMaster $shiftMaster): bool
    {
        return $authUser->can('Replicate:ShiftMaster');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ShiftMaster');
    }

    public function viewOwn(AuthUser $authUser, ShiftMaster $shiftMaster): bool
    {
        return $authUser->can('ViewOwn:ShiftMaster');
    }

}