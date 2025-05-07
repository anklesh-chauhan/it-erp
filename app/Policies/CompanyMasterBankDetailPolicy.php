<?php

namespace App\Policies;

use App\Models\User;
use App\Models\TenantUser;
use App\Models\CompanyMasterBankDetail;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyMasterBankDetailPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User|TenantUser $user): bool
    {
        return $user->can('view_any_company::master::bank::detail');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User|TenantUser $user, CompanyMasterBankDetail $companyMasterBankDetail): bool
    {
        return $user->can('view_company::master::bank::detail');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User|TenantUser $user): bool
    {
        return $user->can('create_company::master::bank::detail');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User|TenantUser $user, CompanyMasterBankDetail $companyMasterBankDetail): bool
    {
        return $user->can('update_company::master::bank::detail');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User|TenantUser $user, CompanyMasterBankDetail $companyMasterBankDetail): bool
    {
        return $user->can('delete_company::master::bank::detail');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User|TenantUser $user): bool
    {
        return $user->can('delete_any_company::master::bank::detail');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User|TenantUser $user, CompanyMasterBankDetail $companyMasterBankDetail): bool
    {
        return $user->can('force_delete_company::master::bank::detail');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User|TenantUser $user): bool
    {
        return $user->can('force_delete_any_company::master::bank::detail');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User|TenantUser $user, CompanyMasterBankDetail $companyMasterBankDetail): bool
    {
        return $user->can('restore_company::master::bank::detail');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User|TenantUser $user): bool
    {
        return $user->can('restore_any_company::master::bank::detail');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User|TenantUser $user, CompanyMasterBankDetail $companyMasterBankDetail): bool
    {
        return $user->can('replicate_company::master::bank::detail');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User|TenantUser $user): bool
    {
        return $user->can('reorder_company::master::bank::detail');
    }
}
