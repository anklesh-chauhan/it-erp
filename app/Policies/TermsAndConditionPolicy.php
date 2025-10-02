<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\TermsAndCondition;
use Illuminate\Auth\Access\HandlesAuthorization;

class TermsAndConditionPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:TermsAndCondition');
    }

    public function view(AuthUser $authUser, TermsAndCondition $termsAndCondition): bool
    {
        return $authUser->can('View:TermsAndCondition');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:TermsAndCondition');
    }

    public function update(AuthUser $authUser, TermsAndCondition $termsAndCondition): bool
    {
        return $authUser->can('Update:TermsAndCondition');
    }

    public function delete(AuthUser $authUser, TermsAndCondition $termsAndCondition): bool
    {
        return $authUser->can('Delete:TermsAndCondition');
    }

    public function restore(AuthUser $authUser, TermsAndCondition $termsAndCondition): bool
    {
        return $authUser->can('Restore:TermsAndCondition');
    }

    public function forceDelete(AuthUser $authUser, TermsAndCondition $termsAndCondition): bool
    {
        return $authUser->can('ForceDelete:TermsAndCondition');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:TermsAndCondition');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:TermsAndCondition');
    }

    public function replicate(AuthUser $authUser, TermsAndCondition $termsAndCondition): bool
    {
        return $authUser->can('Replicate:TermsAndCondition');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:TermsAndCondition');
    }

}