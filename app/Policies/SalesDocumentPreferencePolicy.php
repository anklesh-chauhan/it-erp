<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\SalesDocumentPreference;
use Illuminate\Auth\Access\HandlesAuthorization;

class SalesDocumentPreferencePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:SalesDocumentPreference');
    }

    public function view(AuthUser $authUser, SalesDocumentPreference $salesDocumentPreference): bool
    {
        return $authUser->can('View:SalesDocumentPreference');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:SalesDocumentPreference');
    }

    public function update(AuthUser $authUser, SalesDocumentPreference $salesDocumentPreference): bool
    {
        return $authUser->can('Update:SalesDocumentPreference');
    }

    public function delete(AuthUser $authUser, SalesDocumentPreference $salesDocumentPreference): bool
    {
        return $authUser->can('Delete:SalesDocumentPreference');
    }

    public function restore(AuthUser $authUser, SalesDocumentPreference $salesDocumentPreference): bool
    {
        return $authUser->can('Restore:SalesDocumentPreference');
    }

    public function forceDelete(AuthUser $authUser, SalesDocumentPreference $salesDocumentPreference): bool
    {
        return $authUser->can('ForceDelete:SalesDocumentPreference');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:SalesDocumentPreference');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:SalesDocumentPreference');
    }

    public function replicate(AuthUser $authUser, SalesDocumentPreference $salesDocumentPreference): bool
    {
        return $authUser->can('Replicate:SalesDocumentPreference');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:SalesDocumentPreference');
    }

    public function viewOwnTerritory(AuthUser $authUser, SalesDocumentPreference $salesDocumentPreference): bool
    {
        return $authUser->can('ViewOwnTerritory:SalesDocumentPreference');
    }

    public function viewOwnOU(AuthUser $authUser, SalesDocumentPreference $salesDocumentPreference): bool
    {
        return $authUser->can('ViewOwnOU:SalesDocumentPreference');
    }

    public function viewOwn(AuthUser $authUser, SalesDocumentPreference $salesDocumentPreference): bool
    {
        return $authUser->can('ViewOwn:SalesDocumentPreference');
    }

}