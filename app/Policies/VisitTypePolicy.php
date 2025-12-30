<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\VisitType;
use Illuminate\Auth\Access\HandlesAuthorization;

class VisitTypePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:VisitType');
    }

    public function view(AuthUser $authUser, VisitType $visitType): bool
    {
        return $authUser->can('View:VisitType');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:VisitType');
    }

    public function update(AuthUser $authUser, VisitType $visitType): bool
    {
        return $authUser->can('Update:VisitType');
    }

    public function delete(AuthUser $authUser, VisitType $visitType): bool
    {
        return $authUser->can('Delete:VisitType');
    }

    public function restore(AuthUser $authUser, VisitType $visitType): bool
    {
        return $authUser->can('Restore:VisitType');
    }

    public function forceDelete(AuthUser $authUser, VisitType $visitType): bool
    {
        return $authUser->can('ForceDelete:VisitType');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:VisitType');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:VisitType');
    }

    public function replicate(AuthUser $authUser, VisitType $visitType): bool
    {
        return $authUser->can('Replicate:VisitType');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:VisitType');
    }

    public function viewOwnTerritory(AuthUser $authUser, VisitType $visitType): bool
    {
        return $authUser->can('ViewOwnTerritory:VisitType');
    }

    public function viewOwn(AuthUser $authUser, VisitType $visitType): bool
    {
        return $authUser->can('ViewOwn:VisitType');
    }

}