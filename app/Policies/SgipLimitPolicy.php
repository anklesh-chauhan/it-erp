<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\SgipLimit;
use Illuminate\Auth\Access\HandlesAuthorization;

class SgipLimitPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:SgipLimit');
    }

    public function view(AuthUser $authUser, SgipLimit $sgipLimit): bool
    {
        return $authUser->can('View:SgipLimit');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:SgipLimit');
    }

    public function update(AuthUser $authUser, SgipLimit $sgipLimit): bool
    {
        return $authUser->can('Update:SgipLimit');
    }

    public function delete(AuthUser $authUser, SgipLimit $sgipLimit): bool
    {
        return $authUser->can('Delete:SgipLimit');
    }

    public function restore(AuthUser $authUser, SgipLimit $sgipLimit): bool
    {
        return $authUser->can('Restore:SgipLimit');
    }

    public function forceDelete(AuthUser $authUser, SgipLimit $sgipLimit): bool
    {
        return $authUser->can('ForceDelete:SgipLimit');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:SgipLimit');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:SgipLimit');
    }

    public function replicate(AuthUser $authUser, SgipLimit $sgipLimit): bool
    {
        return $authUser->can('Replicate:SgipLimit');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:SgipLimit');
    }

    public function viewOwnTerritory(AuthUser $authUser, SgipLimit $sgipLimit): bool
    {
        return $authUser->can('ViewOwnTerritory:SgipLimit');
    }

    public function viewOwnOU(AuthUser $authUser, SgipLimit $sgipLimit): bool
    {
        return $authUser->can('ViewOwnOU:SgipLimit');
    }

    public function viewOwn(AuthUser $authUser, SgipLimit $sgipLimit): bool
    {
        return $authUser->can('ViewOwn:SgipLimit');
    }

    public function overrideApproval(AuthUser $authUser, SgipLimit $sgipLimit): bool
    {
        return $authUser->can('OverrideApproval:SgipLimit');
    }

}