<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\LeaveApplication;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeaveApplicationPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:LeaveApplication');
    }

    public function view(AuthUser $authUser, LeaveApplication $leaveApplication): bool
    {
        return $authUser->can('View:LeaveApplication');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:LeaveApplication');
    }

    public function update(AuthUser $authUser, LeaveApplication $leaveApplication): bool
    {
        return $authUser->can('Update:LeaveApplication');
    }

    public function delete(AuthUser $authUser, LeaveApplication $leaveApplication): bool
    {
        return $authUser->can('Delete:LeaveApplication');
    }

    public function restore(AuthUser $authUser, LeaveApplication $leaveApplication): bool
    {
        return $authUser->can('Restore:LeaveApplication');
    }

    public function forceDelete(AuthUser $authUser, LeaveApplication $leaveApplication): bool
    {
        return $authUser->can('ForceDelete:LeaveApplication');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:LeaveApplication');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:LeaveApplication');
    }

    public function replicate(AuthUser $authUser, LeaveApplication $leaveApplication): bool
    {
        return $authUser->can('Replicate:LeaveApplication');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:LeaveApplication');
    }

    public function viewOwnTerritory(AuthUser $authUser, LeaveApplication $leaveApplication): bool
    {
        return $authUser->can('ViewOwnTerritory:LeaveApplication');
    }

    public function viewOwnOU(AuthUser $authUser, LeaveApplication $leaveApplication): bool
    {
        return $authUser->can('ViewOwnOU:LeaveApplication');
    }

    public function viewOwn(AuthUser $authUser, LeaveApplication $leaveApplication): bool
    {
        return $authUser->can('ViewOwn:LeaveApplication');
    }

}