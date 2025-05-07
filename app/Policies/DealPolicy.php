<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TenantUser;
use App\Models\Deal;
use Illuminate\Auth\Access\HandlesAuthorization;

class DealPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User|TenantUser $user): bool
    {
        return $user->can('view_any_deal');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User|TenantUser $user, Deal $deal): bool
    {
        return $user->can('view_deal');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User|TenantUser $user): bool
    {
        return $user->can('create_deal');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User|TenantUser $user, Deal $deal): bool
    {
        return $user->can('update_deal');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User|TenantUser $user, Deal $deal): bool
    {
        return $user->can('delete_deal');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User|TenantUser $user): bool
    {
        return $user->can('delete_any_deal');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User|TenantUser $user, Deal $deal): bool
    {
        return $user->can('force_delete_deal');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User|TenantUser $user): bool
    {
        return $user->can('force_delete_any_deal');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User|TenantUser $user, Deal $deal): bool
    {
        return $user->can('restore_deal');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User|TenantUser $user): bool
    {
        return $user->can('restore_any_deal');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User|TenantUser $user, Deal $deal): bool
    {
        return $user->can('replicate_deal');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User|TenantUser $user): bool
    {
        return $user->can('reorder_deal');
    }
}
