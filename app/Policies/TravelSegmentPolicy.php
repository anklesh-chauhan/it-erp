<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\TravelSegment;
use Illuminate\Auth\Access\HandlesAuthorization;

class TravelSegmentPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:TravelSegment');
    }

    public function view(AuthUser $authUser, TravelSegment $travelSegment): bool
    {
        return $authUser->can('View:TravelSegment');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:TravelSegment');
    }

    public function update(AuthUser $authUser, TravelSegment $travelSegment): bool
    {
        return $authUser->can('Update:TravelSegment');
    }

    public function delete(AuthUser $authUser, TravelSegment $travelSegment): bool
    {
        return $authUser->can('Delete:TravelSegment');
    }

    public function restore(AuthUser $authUser, TravelSegment $travelSegment): bool
    {
        return $authUser->can('Restore:TravelSegment');
    }

    public function forceDelete(AuthUser $authUser, TravelSegment $travelSegment): bool
    {
        return $authUser->can('ForceDelete:TravelSegment');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:TravelSegment');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:TravelSegment');
    }

    public function replicate(AuthUser $authUser, TravelSegment $travelSegment): bool
    {
        return $authUser->can('Replicate:TravelSegment');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:TravelSegment');
    }

    public function viewOwnTerritory(AuthUser $authUser, TravelSegment $travelSegment): bool
    {
        return $authUser->can('ViewOwnTerritory:TravelSegment');
    }

    public function viewOwnOU(AuthUser $authUser, TravelSegment $travelSegment): bool
    {
        return $authUser->can('ViewOwnOU:TravelSegment');
    }

    public function viewOwn(AuthUser $authUser, TravelSegment $travelSegment): bool
    {
        return $authUser->can('ViewOwn:TravelSegment');
    }

    public function overrideApproval(AuthUser $authUser, TravelSegment $travelSegment): bool
    {
        return $authUser->can('OverrideApproval:TravelSegment');
    }

}