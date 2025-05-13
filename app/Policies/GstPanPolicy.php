<?php

namespace App\Policies;

use App\Models\User;
use App\Models\GstPan;
use Illuminate\Auth\Access\HandlesAuthorization;

class GstPanPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_gst::pan');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, GstPan $gstPan): bool
    {
        return $user->can('view_gst::pan');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_gst::pan');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, GstPan $gstPan): bool
    {
        return $user->can('update_gst::pan');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, GstPan $gstPan): bool
    {
        return $user->can('delete_gst::pan');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_gst::pan');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, GstPan $gstPan): bool
    {
        return $user->can('force_delete_gst::pan');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_gst::pan');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, GstPan $gstPan): bool
    {
        return $user->can('restore_gst::pan');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_gst::pan');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, GstPan $gstPan): bool
    {
        return $user->can('replicate_gst::pan');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_gst::pan');
    }
}
