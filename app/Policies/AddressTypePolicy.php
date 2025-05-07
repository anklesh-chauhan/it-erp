<?php

namespace App\Policies;

use App\Models\User;
use App\Models\AddressType;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\TenantUser;

class AddressTypePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the User|TenantUser can view any models.
     */
    public function viewAny(User|TenantUser $user): bool
    {
        return $user->can('view_any_address::type');
    }

    /**
     * Determine whether the User|TenantUser can view the model.
     */
    public function view(User|TenantUser $user, AddressType $addressType): bool
    {
        return $user->can('view_address::type');
    }

    /**
     * Determine whether the User|TenantUser can create models.
     */
    public function create(User|TenantUser $user): bool
    {
        return $user->can('create_address::type');
    }

    /**
     * Determine whether the User|TenantUser can update the model.
     */
    public function update(User|TenantUser $user, AddressType $addressType): bool
    {
        return $user->can('update_address::type');
    }

    /**
     * Determine whether the User|TenantUser can delete the model.
     */
    public function delete(User|TenantUser $user, AddressType $addressType): bool
    {
        return $user->can('delete_address::type');
    }

    /**
     * Determine whether the User|TenantUser can bulk delete.
     */
    public function deleteAny(User|TenantUser $user): bool
    {
        return $user->can('delete_any_address::type');
    }

    /**
     * Determine whether the User|TenantUser can permanently delete.
     */
    public function forceDelete(User|TenantUser $user, AddressType $addressType): bool
    {
        return $user->can('force_delete_address::type');
    }

    /**
     * Determine whether the User|TenantUser can permanently bulk delete.
     */
    public function forceDeleteAny(User|TenantUser $user): bool
    {
        return $user->can('force_delete_any_address::type');
    }

    /**
     * Determine whether the User|TenantUser can restore.
     */
    public function restore(User|TenantUser $user, AddressType $addressType): bool
    {
        return $user->can('restore_address::type');
    }

    /**
     * Determine whether the User|TenantUser can bulk restore.
     */
    public function restoreAny(User|TenantUser $user): bool
    {
        return $user->can('restore_any_address::type');
    }

    /**
     * Determine whether the User|TenantUser can replicate.
     */
    public function replicate(User|TenantUser $user, AddressType $addressType): bool
    {
        return $user->can('replicate_address::type');
    }

    /**
     * Determine whether the User|TenantUser can reorder.
     */
    public function reorder(User|TenantUser $user): bool
    {
        return $user->can('reorder_address::type');
    }
}
