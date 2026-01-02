<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\FollowUpMedia;
use Illuminate\Auth\Access\HandlesAuthorization;

class FollowUpMediaPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:FollowUpMedia');
    }

    public function view(AuthUser $authUser, FollowUpMedia $followUpMedia): bool
    {
        return $authUser->can('View:FollowUpMedia');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:FollowUpMedia');
    }

    public function update(AuthUser $authUser, FollowUpMedia $followUpMedia): bool
    {
        return $authUser->can('Update:FollowUpMedia');
    }

    public function delete(AuthUser $authUser, FollowUpMedia $followUpMedia): bool
    {
        return $authUser->can('Delete:FollowUpMedia');
    }

    public function restore(AuthUser $authUser, FollowUpMedia $followUpMedia): bool
    {
        return $authUser->can('Restore:FollowUpMedia');
    }

    public function forceDelete(AuthUser $authUser, FollowUpMedia $followUpMedia): bool
    {
        return $authUser->can('ForceDelete:FollowUpMedia');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:FollowUpMedia');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:FollowUpMedia');
    }

    public function replicate(AuthUser $authUser, FollowUpMedia $followUpMedia): bool
    {
        return $authUser->can('Replicate:FollowUpMedia');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:FollowUpMedia');
    }

    public function viewOwnTerritory(AuthUser $authUser, FollowUpMedia $followUpMedia): bool
    {
        return $authUser->can('ViewOwnTerritory:FollowUpMedia');
    }

    public function viewOwnOU(AuthUser $authUser, FollowUpMedia $followUpMedia): bool
    {
        return $authUser->can('ViewOwnOU:FollowUpMedia');
    }

    public function viewOwn(AuthUser $authUser, FollowUpMedia $followUpMedia): bool
    {
        return $authUser->can('ViewOwn:FollowUpMedia');
    }

}