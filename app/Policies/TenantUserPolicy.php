<?php

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class TenantUserPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:TenantUser');
    }

    public function view(AuthUser $authUser): bool
    {
        return $authUser->can('View:TenantUser');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:TenantUser');
    }

    public function update(AuthUser $authUser): bool
    {
        return $authUser->can('Update:TenantUser');
    }

    public function delete(AuthUser $authUser): bool
    {
        return $authUser->can('Delete:TenantUser');
    }

    public function restore(AuthUser $authUser): bool
    {
        return $authUser->can('Restore:TenantUser');
    }

    public function forceDelete(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDelete:TenantUser');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:TenantUser');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:TenantUser');
    }

    public function replicate(AuthUser $authUser): bool
    {
        return $authUser->can('Replicate:TenantUser');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:TenantUser');
    }

    public function viewOwnTerritory(AuthUser $authUser): bool
    {
        return $authUser->can('ViewOwnTerritory:TenantUser');
    }

    public function viewOwnOU(AuthUser $authUser): bool
    {
        return $authUser->can('ViewOwnOU:TenantUser');
    }

    public function viewOwn(AuthUser $authUser): bool
    {
        return $authUser->can('ViewOwn:TenantUser');
    }

}