<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TenantUser;
use App\Models\Department;
use Illuminate\Auth\Access\HandlesAuthorization;

class DepartmentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User|TenantUser $user): bool
    {
        return $user->can('view_any_department');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User|TenantUser $user, Department $department): bool
    {
        return $user->can('view_department');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User|TenantUser $user): bool
    {
        return $user->can('create_department');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User|TenantUser $user, Department $department): bool
    {
        return $user->can('update_department');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User|TenantUser $user, Department $department): bool
    {
        return $user->can('delete_department');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User|TenantUser $user): bool
    {
        return $user->can('delete_any_department');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User|TenantUser $user, Department $department): bool
    {
        return $user->can('force_delete_department');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User|TenantUser $user): bool
    {
        return $user->can('force_delete_any_department');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User|TenantUser $user, Department $department): bool
    {
        return $user->can('restore_department');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User|TenantUser $user): bool
    {
        return $user->can('restore_any_department');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User|TenantUser $user, Department $department): bool
    {
        return $user->can('replicate_department');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User|TenantUser $user): bool
    {
        return $user->can('reorder_department');
    }
}
