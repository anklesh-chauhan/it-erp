<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\GstPan;
use Illuminate\Auth\Access\HandlesAuthorization;

class GstPanPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:GstPan');
    }

    public function view(AuthUser $authUser, GstPan $gstPan): bool
    {
        return $authUser->can('View:GstPan');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:GstPan');
    }

    public function update(AuthUser $authUser, GstPan $gstPan): bool
    {
        return $authUser->can('Update:GstPan');
    }

    public function delete(AuthUser $authUser, GstPan $gstPan): bool
    {
        return $authUser->can('Delete:GstPan');
    }

    public function restore(AuthUser $authUser, GstPan $gstPan): bool
    {
        return $authUser->can('Restore:GstPan');
    }

    public function forceDelete(AuthUser $authUser, GstPan $gstPan): bool
    {
        return $authUser->can('ForceDelete:GstPan');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:GstPan');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:GstPan');
    }

    public function replicate(AuthUser $authUser, GstPan $gstPan): bool
    {
        return $authUser->can('Replicate:GstPan');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:GstPan');
    }

    public function viewOwnTerritory(AuthUser $authUser, GstPan $gstPan): bool
    {
        return $authUser->can('ViewOwnTerritory:GstPan');
    }

    public function viewOwn(AuthUser $authUser, GstPan $gstPan): bool
    {
        return $authUser->can('ViewOwn:GstPan');
    }

}