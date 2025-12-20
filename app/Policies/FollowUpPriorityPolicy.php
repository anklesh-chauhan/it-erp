<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\FollowUpPriority;
use Illuminate\Auth\Access\HandlesAuthorization;

class FollowUpPriorityPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:FollowUpPriority');
    }

    public function view(AuthUser $authUser, FollowUpPriority $followUpPriority): bool
    {
        return $authUser->can('View:FollowUpPriority');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:FollowUpPriority');
    }

    public function update(AuthUser $authUser, FollowUpPriority $followUpPriority): bool
    {
        return $authUser->can('Update:FollowUpPriority');
    }

    public function delete(AuthUser $authUser, FollowUpPriority $followUpPriority): bool
    {
        return $authUser->can('Delete:FollowUpPriority');
    }

    public function restore(AuthUser $authUser, FollowUpPriority $followUpPriority): bool
    {
        return $authUser->can('Restore:FollowUpPriority');
    }

    public function forceDelete(AuthUser $authUser, FollowUpPriority $followUpPriority): bool
    {
        return $authUser->can('ForceDelete:FollowUpPriority');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:FollowUpPriority');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:FollowUpPriority');
    }

    public function replicate(AuthUser $authUser, FollowUpPriority $followUpPriority): bool
    {
        return $authUser->can('Replicate:FollowUpPriority');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:FollowUpPriority');
    }

    public function viewOwn(AuthUser $authUser, FollowUpPriority $followUpPriority): bool
    {
        return $authUser->can('ViewOwn:FollowUpPriority');
    }

}