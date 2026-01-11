<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\LeaveLapseRecord;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeaveLapseRecordPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:LeaveLapseRecord');
    }

    public function view(AuthUser $authUser, LeaveLapseRecord $leaveLapseRecord): bool
    {
        return $authUser->can('View:LeaveLapseRecord');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:LeaveLapseRecord');
    }

    public function update(AuthUser $authUser, LeaveLapseRecord $leaveLapseRecord): bool
    {
        return $authUser->can('Update:LeaveLapseRecord');
    }

    public function delete(AuthUser $authUser, LeaveLapseRecord $leaveLapseRecord): bool
    {
        return $authUser->can('Delete:LeaveLapseRecord');
    }

    public function restore(AuthUser $authUser, LeaveLapseRecord $leaveLapseRecord): bool
    {
        return $authUser->can('Restore:LeaveLapseRecord');
    }

    public function forceDelete(AuthUser $authUser, LeaveLapseRecord $leaveLapseRecord): bool
    {
        return $authUser->can('ForceDelete:LeaveLapseRecord');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:LeaveLapseRecord');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:LeaveLapseRecord');
    }

    public function replicate(AuthUser $authUser, LeaveLapseRecord $leaveLapseRecord): bool
    {
        return $authUser->can('Replicate:LeaveLapseRecord');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:LeaveLapseRecord');
    }

    public function viewOwnTerritory(AuthUser $authUser, LeaveLapseRecord $leaveLapseRecord): bool
    {
        return $authUser->can('ViewOwnTerritory:LeaveLapseRecord');
    }

    public function viewOwnOU(AuthUser $authUser, LeaveLapseRecord $leaveLapseRecord): bool
    {
        return $authUser->can('ViewOwnOU:LeaveLapseRecord');
    }

    public function viewOwn(AuthUser $authUser, LeaveLapseRecord $leaveLapseRecord): bool
    {
        return $authUser->can('ViewOwn:LeaveLapseRecord');
    }

}