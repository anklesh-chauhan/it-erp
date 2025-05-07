<?php

namespace App\Policies;

use App\Models\User;
use App\Models\AccountMaster;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\TenantUser;

class AccountMasterPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the User|TenantUser can view any models.
     */
    public function viewAny(User|TenantUser $user): bool
    {
        return $user->can('view_any_account::master');
    }

    /**
     * Determine whether the User|TenantUser can view the model.
     */
    public function view(User|TenantUser $user, AccountMaster $accountMaster): bool
    {
        return $user->can('view_account::master');
    }

    /**
     * Determine whether the User|TenantUser can create models.
     */
    public function create(User|TenantUser $user): bool
    {
        return $user->can('create_account::master');
    }

    /**
     * Determine whether the User|TenantUser can update the model.
     */
    public function update(User|TenantUser $user, AccountMaster $accountMaster): bool
    {
        return $user->can('update_account::master');
    }

    /**
     * Determine whether the User|TenantUser can delete the model.
     */
    public function delete(User|TenantUser $user, AccountMaster $accountMaster): bool
    {
        return $user->can('delete_account::master');
    }

    /**
     * Determine whether the User|TenantUser can bulk delete.
     */
    public function deleteAny(User|TenantUser $user): bool
    {
        return $user->can('delete_any_account::master');
    }

    /**
     * Determine whether the User|TenantUser can permanently delete.
     */
    public function forceDelete(User|TenantUser $user, AccountMaster $accountMaster): bool
    {
        return $user->can('force_delete_account::master');
    }

    /**
     * Determine whether the User|TenantUser can permanently bulk delete.
     */
    public function forceDeleteAny(User|TenantUser $user): bool
    {
        return $user->can('force_delete_any_account::master');
    }

    /**
     * Determine whether the User|TenantUser can restore.
     */
    public function restore(User|TenantUser $user, AccountMaster $accountMaster): bool
    {
        return $user->can('restore_account::master');
    }

    /**
     * Determine whether the User|TenantUser can bulk restore.
     */
    public function restoreAny(User|TenantUser $user): bool
    {
        return $user->can('restore_any_account::master');
    }

    /**
     * Determine whether the User|TenantUser can replicate.
     */
    public function replicate(User|TenantUser $user, AccountMaster $accountMaster): bool
    {
        return $user->can('replicate_account::master');
    }

    /**
     * Determine whether the User|TenantUser can reorder.
     */
    public function reorder(User|TenantUser $user): bool
    {
        return $user->can('reorder_account::master');
    }
}
