<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\LeaveRule;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeaveRulePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:LeaveRule');
    }

    public function view(AuthUser $authUser, LeaveRule $leaveRule): bool
    {
        return $authUser->can('View:LeaveRule');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:LeaveRule');
    }

    public function update(AuthUser $authUser, LeaveRule $leaveRule): bool
    {
        return $authUser->can('Update:LeaveRule');
    }

    public function delete(AuthUser $authUser, LeaveRule $leaveRule): bool
    {
        return $authUser->can('Delete:LeaveRule');
    }

    public function restore(AuthUser $authUser, LeaveRule $leaveRule): bool
    {
        return $authUser->can('Restore:LeaveRule');
    }

    public function forceDelete(AuthUser $authUser, LeaveRule $leaveRule): bool
    {
        return $authUser->can('ForceDelete:LeaveRule');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:LeaveRule');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:LeaveRule');
    }

    public function replicate(AuthUser $authUser, LeaveRule $leaveRule): bool
    {
        return $authUser->can('Replicate:LeaveRule');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:LeaveRule');
    }

    public function viewOwnTerritory(AuthUser $authUser, LeaveRule $leaveRule): bool
    {
        return $authUser->can('ViewOwnTerritory:LeaveRule');
    }

    public function viewOwnOU(AuthUser $authUser, LeaveRule $leaveRule): bool
    {
        return $authUser->can('ViewOwnOU:LeaveRule');
    }

    public function viewOwn(AuthUser $authUser, LeaveRule $leaveRule): bool
    {
        return $authUser->can('ViewOwn:LeaveRule');
    }

}