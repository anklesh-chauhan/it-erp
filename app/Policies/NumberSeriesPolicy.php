<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\NumberSeries;
use Illuminate\Auth\Access\HandlesAuthorization;

class NumberSeriesPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:NumberSeries');
    }

    public function view(AuthUser $authUser, NumberSeries $numberSeries): bool
    {
        return $authUser->can('View:NumberSeries');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:NumberSeries');
    }

    public function update(AuthUser $authUser, NumberSeries $numberSeries): bool
    {
        return $authUser->can('Update:NumberSeries');
    }

    public function delete(AuthUser $authUser, NumberSeries $numberSeries): bool
    {
        return $authUser->can('Delete:NumberSeries');
    }

    public function restore(AuthUser $authUser, NumberSeries $numberSeries): bool
    {
        return $authUser->can('Restore:NumberSeries');
    }

    public function forceDelete(AuthUser $authUser, NumberSeries $numberSeries): bool
    {
        return $authUser->can('ForceDelete:NumberSeries');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:NumberSeries');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:NumberSeries');
    }

    public function replicate(AuthUser $authUser, NumberSeries $numberSeries): bool
    {
        return $authUser->can('Replicate:NumberSeries');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:NumberSeries');
    }

    public function viewOwnTerritory(AuthUser $authUser, NumberSeries $numberSeries): bool
    {
        return $authUser->can('ViewOwnTerritory:NumberSeries');
    }

    public function viewOwnOU(AuthUser $authUser, NumberSeries $numberSeries): bool
    {
        return $authUser->can('ViewOwnOU:NumberSeries');
    }

    public function viewOwn(AuthUser $authUser, NumberSeries $numberSeries): bool
    {
        return $authUser->can('ViewOwn:NumberSeries');
    }

}