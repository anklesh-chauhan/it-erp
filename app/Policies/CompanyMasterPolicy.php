<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\CompanyMaster;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyMasterPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CompanyMaster');
    }

    public function view(AuthUser $authUser, CompanyMaster $companyMaster): bool
    {
        return $authUser->can('View:CompanyMaster');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CompanyMaster');
    }

    public function update(AuthUser $authUser, CompanyMaster $companyMaster): bool
    {
        return $authUser->can('Update:CompanyMaster');
    }

    public function delete(AuthUser $authUser, CompanyMaster $companyMaster): bool
    {
        return $authUser->can('Delete:CompanyMaster');
    }

    public function restore(AuthUser $authUser, CompanyMaster $companyMaster): bool
    {
        return $authUser->can('Restore:CompanyMaster');
    }

    public function forceDelete(AuthUser $authUser, CompanyMaster $companyMaster): bool
    {
        return $authUser->can('ForceDelete:CompanyMaster');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CompanyMaster');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:CompanyMaster');
    }

    public function replicate(AuthUser $authUser, CompanyMaster $companyMaster): bool
    {
        return $authUser->can('Replicate:CompanyMaster');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:CompanyMaster');
    }

    public function viewOwn(AuthUser $authUser, CompanyMaster $companyMaster): bool
    {
        return $authUser->can('ViewOwn:CompanyMaster');
    }

}