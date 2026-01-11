<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Patch;
use Illuminate\Auth\Access\HandlesAuthorization;

class PatchPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Patch');
    }

    public function view(AuthUser $authUser, Patch $patch): bool
    {
        return $authUser->can('View:Patch');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Patch');
    }

    public function update(AuthUser $authUser, Patch $patch): bool
    {
        return $authUser->can('Update:Patch');
    }

    public function delete(AuthUser $authUser, Patch $patch): bool
    {
        return $authUser->can('Delete:Patch');
    }

    public function restore(AuthUser $authUser, Patch $patch): bool
    {
        return $authUser->can('Restore:Patch');
    }

    public function forceDelete(AuthUser $authUser, Patch $patch): bool
    {
        return $authUser->can('ForceDelete:Patch');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Patch');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Patch');
    }

    public function replicate(AuthUser $authUser, Patch $patch): bool
    {
        return $authUser->can('Replicate:Patch');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Patch');
    }

    public function viewOwnTerritory(AuthUser $authUser, Patch $patch): bool
    {
        return $authUser->can('ViewOwnTerritory:Patch');
    }

    public function viewOwnOU(AuthUser $authUser, Patch $patch): bool
    {
        return $authUser->can('ViewOwnOU:Patch');
    }

    public function viewOwn(AuthUser $authUser, Patch $patch): bool
    {
        return $authUser->can('ViewOwn:Patch');
    }

    public function overrideApproval(AuthUser $authUser, Patch $patch): bool
    {
        return $authUser->can('OverrideApproval:Patch');
    }

}