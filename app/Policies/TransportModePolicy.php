<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\TransportMode;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransportModePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:TransportMode');
    }

    public function view(AuthUser $authUser, TransportMode $transportMode): bool
    {
        return $authUser->can('View:TransportMode');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:TransportMode');
    }

    public function update(AuthUser $authUser, TransportMode $transportMode): bool
    {
        return $authUser->can('Update:TransportMode');
    }

    public function delete(AuthUser $authUser, TransportMode $transportMode): bool
    {
        return $authUser->can('Delete:TransportMode');
    }

    public function restore(AuthUser $authUser, TransportMode $transportMode): bool
    {
        return $authUser->can('Restore:TransportMode');
    }

    public function forceDelete(AuthUser $authUser, TransportMode $transportMode): bool
    {
        return $authUser->can('ForceDelete:TransportMode');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:TransportMode');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:TransportMode');
    }

    public function replicate(AuthUser $authUser, TransportMode $transportMode): bool
    {
        return $authUser->can('Replicate:TransportMode');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:TransportMode');
    }

    public function viewOwnTerritory(AuthUser $authUser, TransportMode $transportMode): bool
    {
        return $authUser->can('ViewOwnTerritory:TransportMode');
    }

    public function viewOwnOU(AuthUser $authUser, TransportMode $transportMode): bool
    {
        return $authUser->can('ViewOwnOU:TransportMode');
    }

    public function viewOwn(AuthUser $authUser, TransportMode $transportMode): bool
    {
        return $authUser->can('ViewOwn:TransportMode');
    }

}