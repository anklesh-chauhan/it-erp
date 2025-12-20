<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\CompanyMasterType;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyMasterTypePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CompanyMasterType');
    }

    public function view(AuthUser $authUser, CompanyMasterType $companyMasterType): bool
    {
        return $authUser->can('View:CompanyMasterType');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CompanyMasterType');
    }

    public function update(AuthUser $authUser, CompanyMasterType $companyMasterType): bool
    {
        return $authUser->can('Update:CompanyMasterType');
    }

    public function delete(AuthUser $authUser, CompanyMasterType $companyMasterType): bool
    {
        return $authUser->can('Delete:CompanyMasterType');
    }

    public function restore(AuthUser $authUser, CompanyMasterType $companyMasterType): bool
    {
        return $authUser->can('Restore:CompanyMasterType');
    }

    public function forceDelete(AuthUser $authUser, CompanyMasterType $companyMasterType): bool
    {
        return $authUser->can('ForceDelete:CompanyMasterType');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CompanyMasterType');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:CompanyMasterType');
    }

    public function replicate(AuthUser $authUser, CompanyMasterType $companyMasterType): bool
    {
        return $authUser->can('Replicate:CompanyMasterType');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:CompanyMasterType');
    }

    public function viewOwn(AuthUser $authUser, CompanyMasterType $companyMasterType): bool
    {
        return $authUser->can('ViewOwn:CompanyMasterType');
    }

}