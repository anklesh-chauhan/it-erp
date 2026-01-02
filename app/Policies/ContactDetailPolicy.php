<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ContactDetail;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContactDetailPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ContactDetail');
    }

    public function view(AuthUser $authUser, ContactDetail $contactDetail): bool
    {
        return $authUser->can('View:ContactDetail');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ContactDetail');
    }

    public function update(AuthUser $authUser, ContactDetail $contactDetail): bool
    {
        return $authUser->can('Update:ContactDetail');
    }

    public function delete(AuthUser $authUser, ContactDetail $contactDetail): bool
    {
        return $authUser->can('Delete:ContactDetail');
    }

    public function restore(AuthUser $authUser, ContactDetail $contactDetail): bool
    {
        return $authUser->can('Restore:ContactDetail');
    }

    public function forceDelete(AuthUser $authUser, ContactDetail $contactDetail): bool
    {
        return $authUser->can('ForceDelete:ContactDetail');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ContactDetail');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ContactDetail');
    }

    public function replicate(AuthUser $authUser, ContactDetail $contactDetail): bool
    {
        return $authUser->can('Replicate:ContactDetail');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ContactDetail');
    }

    public function viewOwnTerritory(AuthUser $authUser, ContactDetail $contactDetail): bool
    {
        return $authUser->can('ViewOwnTerritory:ContactDetail');
    }

    public function viewOwnOU(AuthUser $authUser, ContactDetail $contactDetail): bool
    {
        return $authUser->can('ViewOwnOU:ContactDetail');
    }

    public function viewOwn(AuthUser $authUser, ContactDetail $contactDetail): bool
    {
        return $authUser->can('ViewOwn:ContactDetail');
    }

}