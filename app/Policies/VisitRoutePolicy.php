<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\VisitRoute;
use Illuminate\Auth\Access\HandlesAuthorization;

class VisitRoutePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:VisitRoute');
    }

    public function view(AuthUser $authUser, VisitRoute $visitRoute): bool
    {
        return $authUser->can('View:VisitRoute');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:VisitRoute');
    }

    public function update(AuthUser $authUser, VisitRoute $visitRoute): bool
    {
        return $authUser->can('Update:VisitRoute');
    }

    public function delete(AuthUser $authUser, VisitRoute $visitRoute): bool
    {
        return $authUser->can('Delete:VisitRoute');
    }

    public function restore(AuthUser $authUser, VisitRoute $visitRoute): bool
    {
        return $authUser->can('Restore:VisitRoute');
    }

    public function forceDelete(AuthUser $authUser, VisitRoute $visitRoute): bool
    {
        return $authUser->can('ForceDelete:VisitRoute');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:VisitRoute');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:VisitRoute');
    }

    public function replicate(AuthUser $authUser, VisitRoute $visitRoute): bool
    {
        return $authUser->can('Replicate:VisitRoute');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:VisitRoute');
    }

    public function viewOwnTerritory(AuthUser $authUser, VisitRoute $visitRoute): bool
    {
        return $authUser->can('ViewOwnTerritory:VisitRoute');
    }

    public function viewOwnOU(AuthUser $authUser, VisitRoute $visitRoute): bool
    {
        return $authUser->can('ViewOwnOU:VisitRoute');
    }

    public function viewOwn(AuthUser $authUser, VisitRoute $visitRoute): bool
    {
        return $authUser->can('ViewOwn:VisitRoute');
    }

    public function overrideApproval(AuthUser $authUser, VisitRoute $visitRoute): bool
    {
        return $authUser->can('OverrideApproval:VisitRoute');
    }

}