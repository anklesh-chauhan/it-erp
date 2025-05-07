<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TenantUser;
use App\Models\ItemBrand;
use Illuminate\Auth\Access\HandlesAuthorization;

class ItemBrandPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User|TenantUser $user): bool
    {
        return $user->can('view_any_item::brand');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User|TenantUser $user, ItemBrand $itemBrand): bool
    {
        return $user->can('view_item::brand');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User|TenantUser $user): bool
    {
        return $user->can('create_item::brand');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User|TenantUser $user, ItemBrand $itemBrand): bool
    {
        return $user->can('update_item::brand');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User|TenantUser $user, ItemBrand $itemBrand): bool
    {
        return $user->can('delete_item::brand');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User|TenantUser $user): bool
    {
        return $user->can('delete_any_item::brand');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User|TenantUser $user, ItemBrand $itemBrand): bool
    {
        return $user->can('force_delete_item::brand');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User|TenantUser $user): bool
    {
        return $user->can('force_delete_any_item::brand');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User|TenantUser $user, ItemBrand $itemBrand): bool
    {
        return $user->can('restore_item::brand');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User|TenantUser $user): bool
    {
        return $user->can('restore_any_item::brand');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User|TenantUser $user, ItemBrand $itemBrand): bool
    {
        return $user->can('replicate_item::brand');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User|TenantUser $user): bool
    {
        return $user->can('reorder_item::brand');
    }
}
