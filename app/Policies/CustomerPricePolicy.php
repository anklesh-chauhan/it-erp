<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\CustomerPrice;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPricePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CustomerPrice');
    }

    public function view(AuthUser $authUser, CustomerPrice $customerPrice): bool
    {
        return $authUser->can('View:CustomerPrice');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CustomerPrice');
    }

    public function update(AuthUser $authUser, CustomerPrice $customerPrice): bool
    {
        return $authUser->can('Update:CustomerPrice');
    }

    public function delete(AuthUser $authUser, CustomerPrice $customerPrice): bool
    {
        return $authUser->can('Delete:CustomerPrice');
    }

    public function restore(AuthUser $authUser, CustomerPrice $customerPrice): bool
    {
        return $authUser->can('Restore:CustomerPrice');
    }

    public function forceDelete(AuthUser $authUser, CustomerPrice $customerPrice): bool
    {
        return $authUser->can('ForceDelete:CustomerPrice');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CustomerPrice');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:CustomerPrice');
    }

    public function replicate(AuthUser $authUser, CustomerPrice $customerPrice): bool
    {
        return $authUser->can('Replicate:CustomerPrice');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:CustomerPrice');
    }

    public function viewOwnTerritory(AuthUser $authUser, CustomerPrice $customerPrice): bool
    {
        return $authUser->can('ViewOwnTerritory:CustomerPrice');
    }

    public function viewOwnOU(AuthUser $authUser, CustomerPrice $customerPrice): bool
    {
        return $authUser->can('ViewOwnOU:CustomerPrice');
    }

    public function viewOwn(AuthUser $authUser, CustomerPrice $customerPrice): bool
    {
        return $authUser->can('ViewOwn:CustomerPrice');
    }

    public function overrideApproval(AuthUser $authUser, CustomerPrice $customerPrice): bool
    {
        return $authUser->can('OverrideApproval:CustomerPrice');
    }

}