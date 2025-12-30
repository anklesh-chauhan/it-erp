<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ItemMeasurementUnit;
use Illuminate\Auth\Access\HandlesAuthorization;

class ItemMeasurementUnitPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ItemMeasurementUnit');
    }

    public function view(AuthUser $authUser, ItemMeasurementUnit $itemMeasurementUnit): bool
    {
        return $authUser->can('View:ItemMeasurementUnit');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ItemMeasurementUnit');
    }

    public function update(AuthUser $authUser, ItemMeasurementUnit $itemMeasurementUnit): bool
    {
        return $authUser->can('Update:ItemMeasurementUnit');
    }

    public function delete(AuthUser $authUser, ItemMeasurementUnit $itemMeasurementUnit): bool
    {
        return $authUser->can('Delete:ItemMeasurementUnit');
    }

    public function restore(AuthUser $authUser, ItemMeasurementUnit $itemMeasurementUnit): bool
    {
        return $authUser->can('Restore:ItemMeasurementUnit');
    }

    public function forceDelete(AuthUser $authUser, ItemMeasurementUnit $itemMeasurementUnit): bool
    {
        return $authUser->can('ForceDelete:ItemMeasurementUnit');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ItemMeasurementUnit');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ItemMeasurementUnit');
    }

    public function replicate(AuthUser $authUser, ItemMeasurementUnit $itemMeasurementUnit): bool
    {
        return $authUser->can('Replicate:ItemMeasurementUnit');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ItemMeasurementUnit');
    }

    public function viewOwnTerritory(AuthUser $authUser, ItemMeasurementUnit $itemMeasurementUnit): bool
    {
        return $authUser->can('ViewOwnTerritory:ItemMeasurementUnit');
    }

    public function viewOwn(AuthUser $authUser, ItemMeasurementUnit $itemMeasurementUnit): bool
    {
        return $authUser->can('ViewOwn:ItemMeasurementUnit');
    }

}