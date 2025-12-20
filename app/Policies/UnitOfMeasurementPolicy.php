<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\UnitOfMeasurement;
use Illuminate\Auth\Access\HandlesAuthorization;

class UnitOfMeasurementPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:UnitOfMeasurement');
    }

    public function view(AuthUser $authUser, UnitOfMeasurement $unitOfMeasurement): bool
    {
        return $authUser->can('View:UnitOfMeasurement');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:UnitOfMeasurement');
    }

    public function update(AuthUser $authUser, UnitOfMeasurement $unitOfMeasurement): bool
    {
        return $authUser->can('Update:UnitOfMeasurement');
    }

    public function delete(AuthUser $authUser, UnitOfMeasurement $unitOfMeasurement): bool
    {
        return $authUser->can('Delete:UnitOfMeasurement');
    }

    public function restore(AuthUser $authUser, UnitOfMeasurement $unitOfMeasurement): bool
    {
        return $authUser->can('Restore:UnitOfMeasurement');
    }

    public function forceDelete(AuthUser $authUser, UnitOfMeasurement $unitOfMeasurement): bool
    {
        return $authUser->can('ForceDelete:UnitOfMeasurement');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:UnitOfMeasurement');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:UnitOfMeasurement');
    }

    public function replicate(AuthUser $authUser, UnitOfMeasurement $unitOfMeasurement): bool
    {
        return $authUser->can('Replicate:UnitOfMeasurement');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:UnitOfMeasurement');
    }

    public function viewOwn(AuthUser $authUser, UnitOfMeasurement $unitOfMeasurement): bool
    {
        return $authUser->can('ViewOwn:UnitOfMeasurement');
    }

}