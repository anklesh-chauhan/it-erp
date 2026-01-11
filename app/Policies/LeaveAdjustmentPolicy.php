<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\LeaveAdjustment;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeaveAdjustmentPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:LeaveAdjustment');
    }

    public function view(AuthUser $authUser, LeaveAdjustment $leaveAdjustment): bool
    {
        return $authUser->can('View:LeaveAdjustment');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:LeaveAdjustment');
    }

    public function update(AuthUser $authUser, LeaveAdjustment $leaveAdjustment): bool
    {
        return $authUser->can('Update:LeaveAdjustment');
    }

    public function delete(AuthUser $authUser, LeaveAdjustment $leaveAdjustment): bool
    {
        return $authUser->can('Delete:LeaveAdjustment');
    }

    public function restore(AuthUser $authUser, LeaveAdjustment $leaveAdjustment): bool
    {
        return $authUser->can('Restore:LeaveAdjustment');
    }

    public function forceDelete(AuthUser $authUser, LeaveAdjustment $leaveAdjustment): bool
    {
        return $authUser->can('ForceDelete:LeaveAdjustment');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:LeaveAdjustment');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:LeaveAdjustment');
    }

    public function replicate(AuthUser $authUser, LeaveAdjustment $leaveAdjustment): bool
    {
        return $authUser->can('Replicate:LeaveAdjustment');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:LeaveAdjustment');
    }

    public function viewOwnTerritory(AuthUser $authUser, LeaveAdjustment $leaveAdjustment): bool
    {
        return $authUser->can('ViewOwnTerritory:LeaveAdjustment');
    }

    public function viewOwnOU(AuthUser $authUser, LeaveAdjustment $leaveAdjustment): bool
    {
        return $authUser->can('ViewOwnOU:LeaveAdjustment');
    }

    public function viewOwn(AuthUser $authUser, LeaveAdjustment $leaveAdjustment): bool
    {
        return $authUser->can('ViewOwn:LeaveAdjustment');
    }

    public function overrideApproval(AuthUser $authUser, LeaveAdjustment $leaveAdjustment): bool
    {
        return $authUser->can('OverrideApproval:LeaveAdjustment');
    }

}