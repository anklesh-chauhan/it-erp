<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ItemMaster;
use Illuminate\Auth\Access\HandlesAuthorization;

class ItemMasterPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ItemMaster');
    }

    public function view(AuthUser $authUser, ItemMaster $itemMaster): bool
    {
        return $authUser->can('View:ItemMaster');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ItemMaster');
    }

    public function update(AuthUser $authUser, ItemMaster $itemMaster): bool
    {
        return $authUser->can('Update:ItemMaster');
    }

    public function delete(AuthUser $authUser, ItemMaster $itemMaster): bool
    {
        return $authUser->can('Delete:ItemMaster');
    }

    public function restore(AuthUser $authUser, ItemMaster $itemMaster): bool
    {
        return $authUser->can('Restore:ItemMaster');
    }

    public function forceDelete(AuthUser $authUser, ItemMaster $itemMaster): bool
    {
        return $authUser->can('ForceDelete:ItemMaster');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ItemMaster');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ItemMaster');
    }

    public function replicate(AuthUser $authUser, ItemMaster $itemMaster): bool
    {
        return $authUser->can('Replicate:ItemMaster');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ItemMaster');
    }

    public function viewOwnTerritory(AuthUser $authUser, ItemMaster $itemMaster): bool
    {
        return $authUser->can('ViewOwnTerritory:ItemMaster');
    }

    public function viewOwn(AuthUser $authUser, ItemMaster $itemMaster): bool
    {
        return $authUser->can('ViewOwn:ItemMaster');
    }

}