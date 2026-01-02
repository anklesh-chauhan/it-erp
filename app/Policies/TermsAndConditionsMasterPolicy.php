<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\TermsAndConditionsMaster;
use Illuminate\Auth\Access\HandlesAuthorization;

class TermsAndConditionsMasterPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:TermsAndConditionsMaster');
    }

    public function view(AuthUser $authUser, TermsAndConditionsMaster $termsAndConditionsMaster): bool
    {
        return $authUser->can('View:TermsAndConditionsMaster');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:TermsAndConditionsMaster');
    }

    public function update(AuthUser $authUser, TermsAndConditionsMaster $termsAndConditionsMaster): bool
    {
        return $authUser->can('Update:TermsAndConditionsMaster');
    }

    public function delete(AuthUser $authUser, TermsAndConditionsMaster $termsAndConditionsMaster): bool
    {
        return $authUser->can('Delete:TermsAndConditionsMaster');
    }

    public function restore(AuthUser $authUser, TermsAndConditionsMaster $termsAndConditionsMaster): bool
    {
        return $authUser->can('Restore:TermsAndConditionsMaster');
    }

    public function forceDelete(AuthUser $authUser, TermsAndConditionsMaster $termsAndConditionsMaster): bool
    {
        return $authUser->can('ForceDelete:TermsAndConditionsMaster');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:TermsAndConditionsMaster');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:TermsAndConditionsMaster');
    }

    public function replicate(AuthUser $authUser, TermsAndConditionsMaster $termsAndConditionsMaster): bool
    {
        return $authUser->can('Replicate:TermsAndConditionsMaster');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:TermsAndConditionsMaster');
    }

    public function viewOwnTerritory(AuthUser $authUser, TermsAndConditionsMaster $termsAndConditionsMaster): bool
    {
        return $authUser->can('ViewOwnTerritory:TermsAndConditionsMaster');
    }

    public function viewOwnOU(AuthUser $authUser, TermsAndConditionsMaster $termsAndConditionsMaster): bool
    {
        return $authUser->can('ViewOwnOU:TermsAndConditionsMaster');
    }

    public function viewOwn(AuthUser $authUser, TermsAndConditionsMaster $termsAndConditionsMaster): bool
    {
        return $authUser->can('ViewOwn:TermsAndConditionsMaster');
    }

}