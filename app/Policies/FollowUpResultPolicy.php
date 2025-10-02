<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\FollowUpResult;
use Illuminate\Auth\Access\HandlesAuthorization;

class FollowUpResultPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:FollowUpResult');
    }

    public function view(AuthUser $authUser, FollowUpResult $followUpResult): bool
    {
        return $authUser->can('View:FollowUpResult');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:FollowUpResult');
    }

    public function update(AuthUser $authUser, FollowUpResult $followUpResult): bool
    {
        return $authUser->can('Update:FollowUpResult');
    }

    public function delete(AuthUser $authUser, FollowUpResult $followUpResult): bool
    {
        return $authUser->can('Delete:FollowUpResult');
    }

    public function restore(AuthUser $authUser, FollowUpResult $followUpResult): bool
    {
        return $authUser->can('Restore:FollowUpResult');
    }

    public function forceDelete(AuthUser $authUser, FollowUpResult $followUpResult): bool
    {
        return $authUser->can('ForceDelete:FollowUpResult');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:FollowUpResult');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:FollowUpResult');
    }

    public function replicate(AuthUser $authUser, FollowUpResult $followUpResult): bool
    {
        return $authUser->can('Replicate:FollowUpResult');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:FollowUpResult');
    }

}