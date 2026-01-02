<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Image;
use Illuminate\Auth\Access\HandlesAuthorization;

class ImagePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Image');
    }

    public function view(AuthUser $authUser, Image $image): bool
    {
        return $authUser->can('View:Image');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Image');
    }

    public function update(AuthUser $authUser, Image $image): bool
    {
        return $authUser->can('Update:Image');
    }

    public function delete(AuthUser $authUser, Image $image): bool
    {
        return $authUser->can('Delete:Image');
    }

    public function restore(AuthUser $authUser, Image $image): bool
    {
        return $authUser->can('Restore:Image');
    }

    public function forceDelete(AuthUser $authUser, Image $image): bool
    {
        return $authUser->can('ForceDelete:Image');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Image');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Image');
    }

    public function replicate(AuthUser $authUser, Image $image): bool
    {
        return $authUser->can('Replicate:Image');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Image');
    }

    public function viewOwnTerritory(AuthUser $authUser, Image $image): bool
    {
        return $authUser->can('ViewOwnTerritory:Image');
    }

    public function viewOwnOU(AuthUser $authUser, Image $image): bool
    {
        return $authUser->can('ViewOwnOU:Image');
    }

    public function viewOwn(AuthUser $authUser, Image $image): bool
    {
        return $authUser->can('ViewOwn:Image');
    }

}