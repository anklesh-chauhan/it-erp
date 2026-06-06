<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\StandardFareChart;
use Illuminate\Auth\Access\HandlesAuthorization;

class StandardFareChartPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:StandardFareChart');
    }

    public function view(AuthUser $authUser, StandardFareChart $standardFareChart): bool
    {
        return $authUser->can('View:StandardFareChart');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:StandardFareChart');
    }

    public function update(AuthUser $authUser, StandardFareChart $standardFareChart): bool
    {
        return $authUser->can('Update:StandardFareChart');
    }

    public function delete(AuthUser $authUser, StandardFareChart $standardFareChart): bool
    {
        return $authUser->can('Delete:StandardFareChart');
    }

    public function restore(AuthUser $authUser, StandardFareChart $standardFareChart): bool
    {
        return $authUser->can('Restore:StandardFareChart');
    }

    public function forceDelete(AuthUser $authUser, StandardFareChart $standardFareChart): bool
    {
        return $authUser->can('ForceDelete:StandardFareChart');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:StandardFareChart');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:StandardFareChart');
    }

    public function replicate(AuthUser $authUser, StandardFareChart $standardFareChart): bool
    {
        return $authUser->can('Replicate:StandardFareChart');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:StandardFareChart');
    }

    public function viewOwnTerritory(AuthUser $authUser, StandardFareChart $standardFareChart): bool
    {
        return $authUser->can('ViewOwnTerritory:StandardFareChart');
    }

    public function viewOwnOU(AuthUser $authUser, StandardFareChart $standardFareChart): bool
    {
        return $authUser->can('ViewOwnOU:StandardFareChart');
    }

    public function viewOwn(AuthUser $authUser, StandardFareChart $standardFareChart): bool
    {
        return $authUser->can('ViewOwn:StandardFareChart');
    }

    public function overrideApproval(AuthUser $authUser, StandardFareChart $standardFareChart): bool
    {
        return $authUser->can('OverrideApproval:StandardFareChart');
    }

}