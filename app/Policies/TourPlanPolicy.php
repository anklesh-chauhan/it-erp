<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\TourPlan;
use Illuminate\Auth\Access\HandlesAuthorization;

class TourPlanPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:TourPlan');
    }

    public function view(AuthUser $authUser, TourPlan $tourPlan): bool
    {
        return $authUser->can('View:TourPlan');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:TourPlan');
    }

    public function update(AuthUser $authUser, TourPlan $tourPlan): bool
    {
        return $authUser->can('Update:TourPlan');
    }

    public function delete(AuthUser $authUser, TourPlan $tourPlan): bool
    {
        return $authUser->can('Delete:TourPlan');
    }

    public function restore(AuthUser $authUser, TourPlan $tourPlan): bool
    {
        return $authUser->can('Restore:TourPlan');
    }

    public function forceDelete(AuthUser $authUser, TourPlan $tourPlan): bool
    {
        return $authUser->can('ForceDelete:TourPlan');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:TourPlan');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:TourPlan');
    }

    public function replicate(AuthUser $authUser, TourPlan $tourPlan): bool
    {
        return $authUser->can('Replicate:TourPlan');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:TourPlan');
    }

    public function viewOwnTerritory(AuthUser $authUser, TourPlan $tourPlan): bool
    {
        return $authUser->can('ViewOwnTerritory:TourPlan');
    }

    public function viewOwnOU(AuthUser $authUser, TourPlan $tourPlan): bool
    {
        return $authUser->can('ViewOwnOU:TourPlan');
    }

    public function viewOwn(AuthUser $authUser, TourPlan $tourPlan): bool
    {
        return $authUser->can('ViewOwn:TourPlan');
    }

    public function overrideApproval(AuthUser $authUser, TourPlan $tourPlan): bool
    {
        return $authUser->can('OverrideApproval:TourPlan');
    }

}