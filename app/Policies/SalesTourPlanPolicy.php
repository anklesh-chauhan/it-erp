<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\SalesTourPlan;
use Illuminate\Auth\Access\HandlesAuthorization;

class SalesTourPlanPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:SalesTourPlan');
    }

    public function view(AuthUser $authUser, SalesTourPlan $salesTourPlan): bool
    {
        return $authUser->can('View:SalesTourPlan');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:SalesTourPlan');
    }

    public function update(AuthUser $authUser, SalesTourPlan $salesTourPlan): bool
    {
        return $authUser->can('Update:SalesTourPlan');
    }

    public function delete(AuthUser $authUser, SalesTourPlan $salesTourPlan): bool
    {
        return $authUser->can('Delete:SalesTourPlan');
    }

    public function restore(AuthUser $authUser, SalesTourPlan $salesTourPlan): bool
    {
        return $authUser->can('Restore:SalesTourPlan');
    }

    public function forceDelete(AuthUser $authUser, SalesTourPlan $salesTourPlan): bool
    {
        return $authUser->can('ForceDelete:SalesTourPlan');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:SalesTourPlan');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:SalesTourPlan');
    }

    public function replicate(AuthUser $authUser, SalesTourPlan $salesTourPlan): bool
    {
        return $authUser->can('Replicate:SalesTourPlan');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:SalesTourPlan');
    }

    public function viewOwnTerritory(AuthUser $authUser, SalesTourPlan $salesTourPlan): bool
    {
        return $authUser->can('ViewOwnTerritory:SalesTourPlan');
    }

    public function viewOwn(AuthUser $authUser, SalesTourPlan $salesTourPlan): bool
    {
        return $authUser->can('ViewOwn:SalesTourPlan');
    }

}