<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\CompanyMasterBankDetail;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyMasterBankDetailPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CompanyMasterBankDetail');
    }

    public function view(AuthUser $authUser, CompanyMasterBankDetail $companyMasterBankDetail): bool
    {
        return $authUser->can('View:CompanyMasterBankDetail');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CompanyMasterBankDetail');
    }

    public function update(AuthUser $authUser, CompanyMasterBankDetail $companyMasterBankDetail): bool
    {
        return $authUser->can('Update:CompanyMasterBankDetail');
    }

    public function delete(AuthUser $authUser, CompanyMasterBankDetail $companyMasterBankDetail): bool
    {
        return $authUser->can('Delete:CompanyMasterBankDetail');
    }

    public function restore(AuthUser $authUser, CompanyMasterBankDetail $companyMasterBankDetail): bool
    {
        return $authUser->can('Restore:CompanyMasterBankDetail');
    }

    public function forceDelete(AuthUser $authUser, CompanyMasterBankDetail $companyMasterBankDetail): bool
    {
        return $authUser->can('ForceDelete:CompanyMasterBankDetail');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CompanyMasterBankDetail');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:CompanyMasterBankDetail');
    }

    public function replicate(AuthUser $authUser, CompanyMasterBankDetail $companyMasterBankDetail): bool
    {
        return $authUser->can('Replicate:CompanyMasterBankDetail');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:CompanyMasterBankDetail');
    }

}