<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\LeadSource;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeadSourcePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:LeadSource');
    }

    public function view(AuthUser $authUser, LeadSource $leadSource): bool
    {
        return $authUser->can('View:LeadSource');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:LeadSource');
    }

    public function update(AuthUser $authUser, LeadSource $leadSource): bool
    {
        return $authUser->can('Update:LeadSource');
    }

    public function delete(AuthUser $authUser, LeadSource $leadSource): bool
    {
        return $authUser->can('Delete:LeadSource');
    }

    public function restore(AuthUser $authUser, LeadSource $leadSource): bool
    {
        return $authUser->can('Restore:LeadSource');
    }

    public function forceDelete(AuthUser $authUser, LeadSource $leadSource): bool
    {
        return $authUser->can('ForceDelete:LeadSource');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:LeadSource');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:LeadSource');
    }

    public function replicate(AuthUser $authUser, LeadSource $leadSource): bool
    {
        return $authUser->can('Replicate:LeadSource');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:LeadSource');
    }

    public function viewOwnTerritory(AuthUser $authUser, LeadSource $leadSource): bool
    {
        return $authUser->can('ViewOwnTerritory:LeadSource');
    }

    public function viewOwn(AuthUser $authUser, LeadSource $leadSource): bool
    {
        return $authUser->can('ViewOwn:LeadSource');
    }

}