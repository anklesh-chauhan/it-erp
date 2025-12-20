<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\CityPinCode;
use Illuminate\Auth\Access\HandlesAuthorization;

class CityPinCodePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CityPinCode');
    }

    public function view(AuthUser $authUser, CityPinCode $cityPinCode): bool
    {
        return $authUser->can('View:CityPinCode');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CityPinCode');
    }

    public function update(AuthUser $authUser, CityPinCode $cityPinCode): bool
    {
        return $authUser->can('Update:CityPinCode');
    }

    public function delete(AuthUser $authUser, CityPinCode $cityPinCode): bool
    {
        return $authUser->can('Delete:CityPinCode');
    }

    public function restore(AuthUser $authUser, CityPinCode $cityPinCode): bool
    {
        return $authUser->can('Restore:CityPinCode');
    }

    public function forceDelete(AuthUser $authUser, CityPinCode $cityPinCode): bool
    {
        return $authUser->can('ForceDelete:CityPinCode');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CityPinCode');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:CityPinCode');
    }

    public function replicate(AuthUser $authUser, CityPinCode $cityPinCode): bool
    {
        return $authUser->can('Replicate:CityPinCode');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:CityPinCode');
    }

    public function viewOwn(AuthUser $authUser, CityPinCode $cityPinCode): bool
    {
        return $authUser->can('ViewOwn:CityPinCode');
    }

}