<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Patch;
use Illuminate\Auth\Access\HandlesAuthorization;

class PatchPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_patches::patch');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Patch $patch): bool
    {
        return $user->can('view_patches::patch');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_patches::patch');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Patch $patch): bool
    {
        return $user->can('update_patches::patch');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Patch $patch): bool
    {
        return $user->can('delete_patches::patch');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_patches::patch');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Patch $patch): bool
    {
        return $user->can('force_delete_patches::patch');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_patches::patch');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Patch $patch): bool
    {
        return $user->can('restore_patches::patch');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_patches::patch');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Patch $patch): bool
    {
        return $user->can('replicate_patches::patch');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_patches::patch');
    }
}
