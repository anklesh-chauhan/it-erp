<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\LeaveEncashment;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeaveEncashmentPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:LeaveEncashment');
    }

    public function view(AuthUser $authUser, LeaveEncashment $leaveEncashment): bool
    {
        return $authUser->can('View:LeaveEncashment');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:LeaveEncashment');
    }

    public function update(AuthUser $authUser, LeaveEncashment $leaveEncashment): bool
    {
        return $authUser->can('Update:LeaveEncashment');
    }

    public function delete(AuthUser $authUser, LeaveEncashment $leaveEncashment): bool
    {
        return $authUser->can('Delete:LeaveEncashment');
    }

    public function restore(AuthUser $authUser, LeaveEncashment $leaveEncashment): bool
    {
        return $authUser->can('Restore:LeaveEncashment');
    }

    public function forceDelete(AuthUser $authUser, LeaveEncashment $leaveEncashment): bool
    {
        return $authUser->can('ForceDelete:LeaveEncashment');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:LeaveEncashment');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:LeaveEncashment');
    }

    public function replicate(AuthUser $authUser, LeaveEncashment $leaveEncashment): bool
    {
        return $authUser->can('Replicate:LeaveEncashment');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:LeaveEncashment');
    }

    public function viewOwnTerritory(AuthUser $authUser, LeaveEncashment $leaveEncashment): bool
    {
        return $authUser->can('ViewOwnTerritory:LeaveEncashment');
    }

    public function viewOwnOU(AuthUser $authUser, LeaveEncashment $leaveEncashment): bool
    {
        return $authUser->can('ViewOwnOU:LeaveEncashment');
    }

    public function viewOwn(AuthUser $authUser, LeaveEncashment $leaveEncashment): bool
    {
        return $authUser->can('ViewOwn:LeaveEncashment');
    }

}