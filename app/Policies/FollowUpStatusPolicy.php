<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\FollowUpStatus;
use Illuminate\Auth\Access\HandlesAuthorization;

class FollowUpStatusPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:FollowUpStatus');
    }

    public function view(AuthUser $authUser, FollowUpStatus $followUpStatus): bool
    {
        return $authUser->can('View:FollowUpStatus');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:FollowUpStatus');
    }

    public function update(AuthUser $authUser, FollowUpStatus $followUpStatus): bool
    {
        return $authUser->can('Update:FollowUpStatus');
    }

    public function delete(AuthUser $authUser, FollowUpStatus $followUpStatus): bool
    {
        return $authUser->can('Delete:FollowUpStatus');
    }

    public function restore(AuthUser $authUser, FollowUpStatus $followUpStatus): bool
    {
        return $authUser->can('Restore:FollowUpStatus');
    }

    public function forceDelete(AuthUser $authUser, FollowUpStatus $followUpStatus): bool
    {
        return $authUser->can('ForceDelete:FollowUpStatus');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:FollowUpStatus');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:FollowUpStatus');
    }

    public function replicate(AuthUser $authUser, FollowUpStatus $followUpStatus): bool
    {
        return $authUser->can('Replicate:FollowUpStatus');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:FollowUpStatus');
    }

    public function viewOwnTerritory(AuthUser $authUser, FollowUpStatus $followUpStatus): bool
    {
        return $authUser->can('ViewOwnTerritory:FollowUpStatus');
    }

    public function viewOwn(AuthUser $authUser, FollowUpStatus $followUpStatus): bool
    {
        return $authUser->can('ViewOwn:FollowUpStatus');
    }

}