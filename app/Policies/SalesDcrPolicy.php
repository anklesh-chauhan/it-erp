<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\SalesDcr;
use Illuminate\Auth\Access\HandlesAuthorization;

class SalesDcrPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:SalesDcr');
    }

    public function view(AuthUser $authUser, SalesDcr $salesDcr): bool
    {
        return $authUser->can('View:SalesDcr');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:SalesDcr');
    }

    public function update(AuthUser $authUser, SalesDcr $salesDcr): bool
    {
        return $authUser->can('Update:SalesDcr');
    }

    public function delete(AuthUser $authUser, SalesDcr $salesDcr): bool
    {
        return $authUser->can('Delete:SalesDcr');
    }

    public function restore(AuthUser $authUser, SalesDcr $salesDcr): bool
    {
        return $authUser->can('Restore:SalesDcr');
    }

    public function forceDelete(AuthUser $authUser, SalesDcr $salesDcr): bool
    {
        return $authUser->can('ForceDelete:SalesDcr');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:SalesDcr');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:SalesDcr');
    }

    public function replicate(AuthUser $authUser, SalesDcr $salesDcr): bool
    {
        return $authUser->can('Replicate:SalesDcr');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:SalesDcr');
    }

    public function viewOwnTerritory(AuthUser $authUser, SalesDcr $salesDcr): bool
    {
        return $authUser->can('ViewOwnTerritory:SalesDcr');
    }

    public function viewOwn(AuthUser $authUser, SalesDcr $salesDcr): bool
    {
        return $authUser->can('ViewOwn:SalesDcr');
    }

}