<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ItemBrand;
use Illuminate\Auth\Access\HandlesAuthorization;

class ItemBrandPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ItemBrand');
    }

    public function view(AuthUser $authUser, ItemBrand $itemBrand): bool
    {
        return $authUser->can('View:ItemBrand');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ItemBrand');
    }

    public function update(AuthUser $authUser, ItemBrand $itemBrand): bool
    {
        return $authUser->can('Update:ItemBrand');
    }

    public function delete(AuthUser $authUser, ItemBrand $itemBrand): bool
    {
        return $authUser->can('Delete:ItemBrand');
    }

    public function restore(AuthUser $authUser, ItemBrand $itemBrand): bool
    {
        return $authUser->can('Restore:ItemBrand');
    }

    public function forceDelete(AuthUser $authUser, ItemBrand $itemBrand): bool
    {
        return $authUser->can('ForceDelete:ItemBrand');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ItemBrand');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ItemBrand');
    }

    public function replicate(AuthUser $authUser, ItemBrand $itemBrand): bool
    {
        return $authUser->can('Replicate:ItemBrand');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ItemBrand');
    }

    public function viewOwn(AuthUser $authUser, ItemBrand $itemBrand): bool
    {
        return $authUser->can('ViewOwn:ItemBrand');
    }

}