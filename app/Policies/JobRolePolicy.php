<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\JobRole;
use Illuminate\Auth\Access\HandlesAuthorization;

class JobRolePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:JobRole');
    }

    public function view(AuthUser $authUser, JobRole $jobRole): bool
    {
        return $authUser->can('View:JobRole');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:JobRole');
    }

    public function update(AuthUser $authUser, JobRole $jobRole): bool
    {
        return $authUser->can('Update:JobRole');
    }

    public function delete(AuthUser $authUser, JobRole $jobRole): bool
    {
        return $authUser->can('Delete:JobRole');
    }

    public function restore(AuthUser $authUser, JobRole $jobRole): bool
    {
        return $authUser->can('Restore:JobRole');
    }

    public function forceDelete(AuthUser $authUser, JobRole $jobRole): bool
    {
        return $authUser->can('ForceDelete:JobRole');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:JobRole');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:JobRole');
    }

    public function replicate(AuthUser $authUser, JobRole $jobRole): bool
    {
        return $authUser->can('Replicate:JobRole');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:JobRole');
    }

    public function viewOwnTerritory(AuthUser $authUser, JobRole $jobRole): bool
    {
        return $authUser->can('ViewOwnTerritory:JobRole');
    }

    public function viewOwnOU(AuthUser $authUser, JobRole $jobRole): bool
    {
        return $authUser->can('ViewOwnOU:JobRole');
    }

    public function viewOwn(AuthUser $authUser, JobRole $jobRole): bool
    {
        return $authUser->can('ViewOwn:JobRole');
    }

    public function overrideApproval(AuthUser $authUser, JobRole $jobRole): bool
    {
        return $authUser->can('OverrideApproval:JobRole');
    }

}