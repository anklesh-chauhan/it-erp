<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\WeekOff;
use Illuminate\Auth\Access\HandlesAuthorization;

class WeekOffPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:WeekOff');
    }

    public function view(AuthUser $authUser, WeekOff $weekOff): bool
    {
        return $authUser->can('View:WeekOff');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:WeekOff');
    }

    public function update(AuthUser $authUser, WeekOff $weekOff): bool
    {
        return $authUser->can('Update:WeekOff');
    }

    public function delete(AuthUser $authUser, WeekOff $weekOff): bool
    {
        return $authUser->can('Delete:WeekOff');
    }

    public function restore(AuthUser $authUser, WeekOff $weekOff): bool
    {
        return $authUser->can('Restore:WeekOff');
    }

    public function forceDelete(AuthUser $authUser, WeekOff $weekOff): bool
    {
        return $authUser->can('ForceDelete:WeekOff');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:WeekOff');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:WeekOff');
    }

    public function replicate(AuthUser $authUser, WeekOff $weekOff): bool
    {
        return $authUser->can('Replicate:WeekOff');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:WeekOff');
    }

    public function viewOwnTerritory(AuthUser $authUser, WeekOff $weekOff): bool
    {
        return $authUser->can('ViewOwnTerritory:WeekOff');
    }

    public function viewOwnOU(AuthUser $authUser, WeekOff $weekOff): bool
    {
        return $authUser->can('ViewOwnOU:WeekOff');
    }

    public function viewOwn(AuthUser $authUser, WeekOff $weekOff): bool
    {
        return $authUser->can('ViewOwn:WeekOff');
    }

    public function overrideApproval(AuthUser $authUser, WeekOff $weekOff): bool
    {
        return $authUser->can('OverrideApproval:WeekOff');
    }

}