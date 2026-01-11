<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\LeaveInstance;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeaveInstancePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:LeaveInstance');
    }

    public function view(AuthUser $authUser, LeaveInstance $leaveInstance): bool
    {
        return $authUser->can('View:LeaveInstance');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:LeaveInstance');
    }

    public function update(AuthUser $authUser, LeaveInstance $leaveInstance): bool
    {
        return $authUser->can('Update:LeaveInstance');
    }

    public function delete(AuthUser $authUser, LeaveInstance $leaveInstance): bool
    {
        return $authUser->can('Delete:LeaveInstance');
    }

    public function restore(AuthUser $authUser, LeaveInstance $leaveInstance): bool
    {
        return $authUser->can('Restore:LeaveInstance');
    }

    public function forceDelete(AuthUser $authUser, LeaveInstance $leaveInstance): bool
    {
        return $authUser->can('ForceDelete:LeaveInstance');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:LeaveInstance');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:LeaveInstance');
    }

    public function replicate(AuthUser $authUser, LeaveInstance $leaveInstance): bool
    {
        return $authUser->can('Replicate:LeaveInstance');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:LeaveInstance');
    }

    public function viewOwnTerritory(AuthUser $authUser, LeaveInstance $leaveInstance): bool
    {
        return $authUser->can('ViewOwnTerritory:LeaveInstance');
    }

    public function viewOwnOU(AuthUser $authUser, LeaveInstance $leaveInstance): bool
    {
        return $authUser->can('ViewOwnOU:LeaveInstance');
    }

    public function viewOwn(AuthUser $authUser, LeaveInstance $leaveInstance): bool
    {
        return $authUser->can('ViewOwn:LeaveInstance');
    }

    public function overrideApproval(AuthUser $authUser, LeaveInstance $leaveInstance): bool
    {
        return $authUser->can('OverrideApproval:LeaveInstance');
    }

}