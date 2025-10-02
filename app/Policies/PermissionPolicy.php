<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use Spatie\Permission\Models\Permission;
use Illuminate\Auth\Access\HandlesAuthorization;

class PermissionPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Permission');
    }

    public function view(AuthUser $authUser, Permission $permission): bool
    {
        return $authUser->can('View:Permission');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Permission');
    }

    public function update(AuthUser $authUser, Permission $permission): bool
    {
        return $authUser->can('Update:Permission');
    }

    public function delete(AuthUser $authUser, Permission $permission): bool
    {
        return $authUser->can('Delete:Permission');
    }

    public function restore(AuthUser $authUser, Permission $permission): bool
    {
        return $authUser->can('Restore:Permission');
    }

    public function forceDelete(AuthUser $authUser, Permission $permission): bool
    {
        return $authUser->can('ForceDelete:Permission');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Permission');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Permission');
    }

    public function replicate(AuthUser $authUser, Permission $permission): bool
    {
        return $authUser->can('Replicate:Permission');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Permission');
    }

}