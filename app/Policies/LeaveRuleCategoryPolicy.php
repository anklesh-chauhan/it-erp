<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\LeaveRuleCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeaveRuleCategoryPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:LeaveRuleCategory');
    }

    public function view(AuthUser $authUser, LeaveRuleCategory $leaveRuleCategory): bool
    {
        return $authUser->can('View:LeaveRuleCategory');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:LeaveRuleCategory');
    }

    public function update(AuthUser $authUser, LeaveRuleCategory $leaveRuleCategory): bool
    {
        return $authUser->can('Update:LeaveRuleCategory');
    }

    public function delete(AuthUser $authUser, LeaveRuleCategory $leaveRuleCategory): bool
    {
        return $authUser->can('Delete:LeaveRuleCategory');
    }

    public function restore(AuthUser $authUser, LeaveRuleCategory $leaveRuleCategory): bool
    {
        return $authUser->can('Restore:LeaveRuleCategory');
    }

    public function forceDelete(AuthUser $authUser, LeaveRuleCategory $leaveRuleCategory): bool
    {
        return $authUser->can('ForceDelete:LeaveRuleCategory');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:LeaveRuleCategory');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:LeaveRuleCategory');
    }

    public function replicate(AuthUser $authUser, LeaveRuleCategory $leaveRuleCategory): bool
    {
        return $authUser->can('Replicate:LeaveRuleCategory');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:LeaveRuleCategory');
    }

    public function viewOwnTerritory(AuthUser $authUser, LeaveRuleCategory $leaveRuleCategory): bool
    {
        return $authUser->can('ViewOwnTerritory:LeaveRuleCategory');
    }

    public function viewOwnOU(AuthUser $authUser, LeaveRuleCategory $leaveRuleCategory): bool
    {
        return $authUser->can('ViewOwnOU:LeaveRuleCategory');
    }

    public function viewOwn(AuthUser $authUser, LeaveRuleCategory $leaveRuleCategory): bool
    {
        return $authUser->can('ViewOwn:LeaveRuleCategory');
    }

    public function overrideApproval(AuthUser $authUser, LeaveRuleCategory $leaveRuleCategory): bool
    {
        return $authUser->can('OverrideApproval:LeaveRuleCategory');
    }

}