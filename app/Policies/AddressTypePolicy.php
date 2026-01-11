<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\AddressType;
use Illuminate\Auth\Access\HandlesAuthorization;

class AddressTypePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:AddressType');
    }

    public function view(AuthUser $authUser, AddressType $addressType): bool
    {
        return $authUser->can('View:AddressType');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:AddressType');
    }

    public function update(AuthUser $authUser, AddressType $addressType): bool
    {
        return $authUser->can('Update:AddressType');
    }

    public function delete(AuthUser $authUser, AddressType $addressType): bool
    {
        return $authUser->can('Delete:AddressType');
    }

    public function restore(AuthUser $authUser, AddressType $addressType): bool
    {
        return $authUser->can('Restore:AddressType');
    }

    public function forceDelete(AuthUser $authUser, AddressType $addressType): bool
    {
        return $authUser->can('ForceDelete:AddressType');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:AddressType');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:AddressType');
    }

    public function replicate(AuthUser $authUser, AddressType $addressType): bool
    {
        return $authUser->can('Replicate:AddressType');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:AddressType');
    }

    public function viewOwnTerritory(AuthUser $authUser, AddressType $addressType): bool
    {
        return $authUser->can('ViewOwnTerritory:AddressType');
    }

    public function viewOwnOU(AuthUser $authUser, AddressType $addressType): bool
    {
        return $authUser->can('ViewOwnOU:AddressType');
    }

    public function viewOwn(AuthUser $authUser, AddressType $addressType): bool
    {
        return $authUser->can('ViewOwn:AddressType');
    }

    public function overrideApproval(AuthUser $authUser, AddressType $addressType): bool
    {
        return $authUser->can('OverrideApproval:AddressType');
    }

}