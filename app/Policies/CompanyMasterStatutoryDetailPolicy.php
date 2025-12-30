<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\CompanyMasterStatutoryDetail;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyMasterStatutoryDetailPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CompanyMasterStatutoryDetail');
    }

    public function view(AuthUser $authUser, CompanyMasterStatutoryDetail $companyMasterStatutoryDetail): bool
    {
        return $authUser->can('View:CompanyMasterStatutoryDetail');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CompanyMasterStatutoryDetail');
    }

    public function update(AuthUser $authUser, CompanyMasterStatutoryDetail $companyMasterStatutoryDetail): bool
    {
        return $authUser->can('Update:CompanyMasterStatutoryDetail');
    }

    public function delete(AuthUser $authUser, CompanyMasterStatutoryDetail $companyMasterStatutoryDetail): bool
    {
        return $authUser->can('Delete:CompanyMasterStatutoryDetail');
    }

    public function restore(AuthUser $authUser, CompanyMasterStatutoryDetail $companyMasterStatutoryDetail): bool
    {
        return $authUser->can('Restore:CompanyMasterStatutoryDetail');
    }

    public function forceDelete(AuthUser $authUser, CompanyMasterStatutoryDetail $companyMasterStatutoryDetail): bool
    {
        return $authUser->can('ForceDelete:CompanyMasterStatutoryDetail');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CompanyMasterStatutoryDetail');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:CompanyMasterStatutoryDetail');
    }

    public function replicate(AuthUser $authUser, CompanyMasterStatutoryDetail $companyMasterStatutoryDetail): bool
    {
        return $authUser->can('Replicate:CompanyMasterStatutoryDetail');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:CompanyMasterStatutoryDetail');
    }

    public function viewOwnTerritory(AuthUser $authUser, CompanyMasterStatutoryDetail $companyMasterStatutoryDetail): bool
    {
        return $authUser->can('ViewOwnTerritory:CompanyMasterStatutoryDetail');
    }

    public function viewOwn(AuthUser $authUser, CompanyMasterStatutoryDetail $companyMasterStatutoryDetail): bool
    {
        return $authUser->can('ViewOwn:CompanyMasterStatutoryDetail');
    }

}