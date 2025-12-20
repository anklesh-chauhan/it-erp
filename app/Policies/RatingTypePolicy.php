<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\RatingType;
use Illuminate\Auth\Access\HandlesAuthorization;

class RatingTypePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:RatingType');
    }

    public function view(AuthUser $authUser, RatingType $ratingType): bool
    {
        return $authUser->can('View:RatingType');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:RatingType');
    }

    public function update(AuthUser $authUser, RatingType $ratingType): bool
    {
        return $authUser->can('Update:RatingType');
    }

    public function delete(AuthUser $authUser, RatingType $ratingType): bool
    {
        return $authUser->can('Delete:RatingType');
    }

    public function restore(AuthUser $authUser, RatingType $ratingType): bool
    {
        return $authUser->can('Restore:RatingType');
    }

    public function forceDelete(AuthUser $authUser, RatingType $ratingType): bool
    {
        return $authUser->can('ForceDelete:RatingType');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:RatingType');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:RatingType');
    }

    public function replicate(AuthUser $authUser, RatingType $ratingType): bool
    {
        return $authUser->can('Replicate:RatingType');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:RatingType');
    }

    public function viewOwn(AuthUser $authUser, RatingType $ratingType): bool
    {
        return $authUser->can('ViewOwn:RatingType');
    }

}