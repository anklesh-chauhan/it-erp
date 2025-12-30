<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\VisitRouteTourPlan;
use Illuminate\Auth\Access\HandlesAuthorization;

class VisitRouteTourPlanPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:VisitRouteTourPlan');
    }

    public function view(AuthUser $authUser, VisitRouteTourPlan $visitRouteTourPlan): bool
    {
        return $authUser->can('View:VisitRouteTourPlan');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:VisitRouteTourPlan');
    }

    public function update(AuthUser $authUser, VisitRouteTourPlan $visitRouteTourPlan): bool
    {
        return $authUser->can('Update:VisitRouteTourPlan');
    }

    public function delete(AuthUser $authUser, VisitRouteTourPlan $visitRouteTourPlan): bool
    {
        return $authUser->can('Delete:VisitRouteTourPlan');
    }

    public function restore(AuthUser $authUser, VisitRouteTourPlan $visitRouteTourPlan): bool
    {
        return $authUser->can('Restore:VisitRouteTourPlan');
    }

    public function forceDelete(AuthUser $authUser, VisitRouteTourPlan $visitRouteTourPlan): bool
    {
        return $authUser->can('ForceDelete:VisitRouteTourPlan');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:VisitRouteTourPlan');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:VisitRouteTourPlan');
    }

    public function replicate(AuthUser $authUser, VisitRouteTourPlan $visitRouteTourPlan): bool
    {
        return $authUser->can('Replicate:VisitRouteTourPlan');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:VisitRouteTourPlan');
    }

    public function viewOwnTerritory(AuthUser $authUser, VisitRouteTourPlan $visitRouteTourPlan): bool
    {
        return $authUser->can('ViewOwnTerritory:VisitRouteTourPlan');
    }

    public function viewOwn(AuthUser $authUser, VisitRouteTourPlan $visitRouteTourPlan): bool
    {
        return $authUser->can('ViewOwn:VisitRouteTourPlan');
    }

}