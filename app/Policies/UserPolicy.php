<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TenantUser;

use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User|TenantUser $user
     * @return bool
     */
    public function viewAny(User|TenantUser $user): bool
    {
        return $user->can('view_any_user');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User|TenantUser $user
     * @return bool
     */
    public function view(User|TenantUser $user): bool
    {
        return $user->can('view_user');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User|TenantUser $user
     * @return bool
     */
    public function create(User|TenantUser $user): bool
    {
        return $user->can('create_user');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User|TenantUser $user
     * @return bool
     */
    public function update(User|TenantUser $user): bool
    {
        return $user->can('update_user');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User|TenantUser $user
     * @return bool
     */
    public function delete(User|TenantUser $user): bool
    {
        return $user->can('delete_user');
    }

    /**
     * Determine whether the user can bulk delete.
     *
     * @param  \App\Models\User|TenantUser $user
     * @return bool
     */
    public function deleteAny(User|TenantUser $user): bool
    {
        return $user->can('delete_any_user');
    }

    /**
     * Determine whether the user can permanently delete.
     *
     * @param  \App\Models\User|TenantUser $user
     * @return bool
     */
    public function forceDelete(User|TenantUser $user): bool
    {
        return $user->can('force_delete_user');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     *
     * @param  \App\Models\User|TenantUser $user
     * @return bool
     */
    public function forceDeleteAny(User|TenantUser $user): bool
    {
        return $user->can('force_delete_any_user');
    }

    /**
     * Determine whether the user can restore.
     *
     * @param  \App\Models\User|TenantUser $user
     * @return bool
     */
    public function restore(User|TenantUser $user): bool
    {
        return $user->can('restore_user');
    }

    /**
     * Determine whether the user can bulk restore.
     *
     * @param  \App\Models\User|TenantUser $user
     * @return bool
     */
    public function restoreAny(User|TenantUser $user): bool
    {
        return $user->can('restore_any_user');
    }

    /**
     * Determine whether the user can bulk restore.
     *
     * @param  \App\Models\User|TenantUser $user
     * @return bool
     */
    public function replicate(User|TenantUser $user): bool
    {
        return $user->can('replicate_user');
    }

    /**
     * Determine whether the user can reorder.
     *
     * @param  \App\Models\User|TenantUser $user
     * @return bool
     */
    public function reorder(User|TenantUser $user): bool
    {
        return $user->can('reorder_user');
    }
}
