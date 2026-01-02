<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Deal;
use Illuminate\Auth\Access\HandlesAuthorization;

class DealPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Deal');
    }

    public function view(AuthUser $authUser, Deal $deal): bool
    {
        return $authUser->can('View:Deal');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Deal');
    }

    public function update(AuthUser $authUser, Deal $deal): bool
    {
        return $authUser->can('Update:Deal');
    }

    public function delete(AuthUser $authUser, Deal $deal): bool
    {
        return $authUser->can('Delete:Deal');
    }

    public function restore(AuthUser $authUser, Deal $deal): bool
    {
        return $authUser->can('Restore:Deal');
    }

    public function forceDelete(AuthUser $authUser, Deal $deal): bool
    {
        return $authUser->can('ForceDelete:Deal');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Deal');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Deal');
    }

    public function replicate(AuthUser $authUser, Deal $deal): bool
    {
        return $authUser->can('Replicate:Deal');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Deal');
    }

    public function viewOwnTerritory(AuthUser $authUser, Deal $deal): bool
    {
        return $authUser->can('ViewOwnTerritory:Deal');
    }

    public function viewOwnOU(AuthUser $authUser, Deal $deal): bool
    {
        return $authUser->can('ViewOwnOU:Deal');
    }

    public function viewOwn(AuthUser $authUser, Deal $deal): bool
    {
        return $authUser->can('ViewOwn:Deal');
    }

}