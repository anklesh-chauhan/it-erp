<?php

namespace App\Policies;

use App\Models\User;
use App\Models\LocationMaster;
use Illuminate\Auth\Access\HandlesAuthorization;

class LocationMasterPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_location::master');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, LocationMaster $locationMaster): bool
    {
        return $user->can('view_location::master');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_location::master');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, LocationMaster $locationMaster): bool
    {
        return $user->can('update_location::master');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LocationMaster $locationMaster): bool
    {
        return $user->can('delete_location::master');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_location::master');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, LocationMaster $locationMaster): bool
    {
        return $user->can('force_delete_location::master');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_location::master');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, LocationMaster $locationMaster): bool
    {
        return $user->can('restore_location::master');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_location::master');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, LocationMaster $locationMaster): bool
    {
        return $user->can('replicate_location::master');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_location::master');
    }
}
