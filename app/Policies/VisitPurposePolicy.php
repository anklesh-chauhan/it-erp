<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\VisitPurpose;
use Illuminate\Auth\Access\HandlesAuthorization;

class VisitPurposePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:VisitPurpose');
    }

    public function view(AuthUser $authUser, VisitPurpose $visitPurpose): bool
    {
        return $authUser->can('View:VisitPurpose');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:VisitPurpose');
    }

    public function update(AuthUser $authUser, VisitPurpose $visitPurpose): bool
    {
        return $authUser->can('Update:VisitPurpose');
    }

    public function delete(AuthUser $authUser, VisitPurpose $visitPurpose): bool
    {
        return $authUser->can('Delete:VisitPurpose');
    }

    public function restore(AuthUser $authUser, VisitPurpose $visitPurpose): bool
    {
        return $authUser->can('Restore:VisitPurpose');
    }

    public function forceDelete(AuthUser $authUser, VisitPurpose $visitPurpose): bool
    {
        return $authUser->can('ForceDelete:VisitPurpose');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:VisitPurpose');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:VisitPurpose');
    }

    public function replicate(AuthUser $authUser, VisitPurpose $visitPurpose): bool
    {
        return $authUser->can('Replicate:VisitPurpose');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:VisitPurpose');
    }

    public function viewOwnTerritory(AuthUser $authUser, VisitPurpose $visitPurpose): bool
    {
        return $authUser->can('ViewOwnTerritory:VisitPurpose');
    }

    public function viewOwnOU(AuthUser $authUser, VisitPurpose $visitPurpose): bool
    {
        return $authUser->can('ViewOwnOU:VisitPurpose');
    }

    public function viewOwn(AuthUser $authUser, VisitPurpose $visitPurpose): bool
    {
        return $authUser->can('ViewOwn:VisitPurpose');
    }

}