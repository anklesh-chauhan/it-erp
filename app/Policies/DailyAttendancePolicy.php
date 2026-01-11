<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\DailyAttendance;
use Illuminate\Auth\Access\HandlesAuthorization;

class DailyAttendancePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:DailyAttendance');
    }

    public function view(AuthUser $authUser, DailyAttendance $dailyAttendance): bool
    {
        return $authUser->can('View:DailyAttendance');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:DailyAttendance');
    }

    public function update(AuthUser $authUser, DailyAttendance $dailyAttendance): bool
    {
        return $authUser->can('Update:DailyAttendance');
    }

    public function delete(AuthUser $authUser, DailyAttendance $dailyAttendance): bool
    {
        return $authUser->can('Delete:DailyAttendance');
    }

    public function restore(AuthUser $authUser, DailyAttendance $dailyAttendance): bool
    {
        return $authUser->can('Restore:DailyAttendance');
    }

    public function forceDelete(AuthUser $authUser, DailyAttendance $dailyAttendance): bool
    {
        return $authUser->can('ForceDelete:DailyAttendance');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:DailyAttendance');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:DailyAttendance');
    }

    public function replicate(AuthUser $authUser, DailyAttendance $dailyAttendance): bool
    {
        return $authUser->can('Replicate:DailyAttendance');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:DailyAttendance');
    }

    public function viewOwnTerritory(AuthUser $authUser, DailyAttendance $dailyAttendance): bool
    {
        return $authUser->can('ViewOwnTerritory:DailyAttendance');
    }

    public function viewOwnOU(AuthUser $authUser, DailyAttendance $dailyAttendance): bool
    {
        return $authUser->can('ViewOwnOU:DailyAttendance');
    }

    public function viewOwn(AuthUser $authUser, DailyAttendance $dailyAttendance): bool
    {
        return $authUser->can('ViewOwn:DailyAttendance');
    }

    public function overrideApproval(AuthUser $authUser, DailyAttendance $dailyAttendance): bool
    {
        return $authUser->can('OverrideApproval:DailyAttendance');
    }

}