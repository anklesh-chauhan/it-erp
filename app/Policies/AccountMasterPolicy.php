<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\AccountMaster;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccountMasterPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:AccountMaster');
    }

    public function view(AuthUser $authUser, AccountMaster $accountMaster): bool
    {
        return $authUser->can('View:AccountMaster');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:AccountMaster');
    }

    public function update(AuthUser $authUser, AccountMaster $accountMaster): bool
    {
        return $authUser->can('Update:AccountMaster');
    }

    public function delete(AuthUser $authUser, AccountMaster $accountMaster): bool
    {
        return $authUser->can('Delete:AccountMaster');
    }

    public function restore(AuthUser $authUser, AccountMaster $accountMaster): bool
    {
        return $authUser->can('Restore:AccountMaster');
    }

    public function forceDelete(AuthUser $authUser, AccountMaster $accountMaster): bool
    {
        return $authUser->can('ForceDelete:AccountMaster');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:AccountMaster');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:AccountMaster');
    }

    public function replicate(AuthUser $authUser, AccountMaster $accountMaster): bool
    {
        return $authUser->can('Replicate:AccountMaster');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:AccountMaster');
    }

    public function viewOwnTerritory(AuthUser $authUser, AccountMaster $accountMaster): bool
    {
        return $authUser->can('ViewOwnTerritory:AccountMaster');
    }

    public function viewOwnOU(AuthUser $authUser, AccountMaster $accountMaster): bool
    {
        return $authUser->can('ViewOwnOU:AccountMaster');
    }

    public function viewOwn(AuthUser $authUser, AccountMaster $accountMaster): bool
    {
        return $authUser->can('ViewOwn:AccountMaster');
    }

    public function overrideApproval(AuthUser $authUser, AccountMaster $accountMaster): bool
    {
        return $authUser->can('OverrideApproval:AccountMaster');
    }

}