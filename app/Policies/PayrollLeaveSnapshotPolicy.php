<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\PayrollLeaveSnapshot;
use Illuminate\Auth\Access\HandlesAuthorization;

class PayrollLeaveSnapshotPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:PayrollLeaveSnapshot');
    }

    public function view(AuthUser $authUser, PayrollLeaveSnapshot $payrollLeaveSnapshot): bool
    {
        return $authUser->can('View:PayrollLeaveSnapshot');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:PayrollLeaveSnapshot');
    }

    public function update(AuthUser $authUser, PayrollLeaveSnapshot $payrollLeaveSnapshot): bool
    {
        return $authUser->can('Update:PayrollLeaveSnapshot');
    }

    public function delete(AuthUser $authUser, PayrollLeaveSnapshot $payrollLeaveSnapshot): bool
    {
        return $authUser->can('Delete:PayrollLeaveSnapshot');
    }

    public function restore(AuthUser $authUser, PayrollLeaveSnapshot $payrollLeaveSnapshot): bool
    {
        return $authUser->can('Restore:PayrollLeaveSnapshot');
    }

    public function forceDelete(AuthUser $authUser, PayrollLeaveSnapshot $payrollLeaveSnapshot): bool
    {
        return $authUser->can('ForceDelete:PayrollLeaveSnapshot');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:PayrollLeaveSnapshot');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:PayrollLeaveSnapshot');
    }

    public function replicate(AuthUser $authUser, PayrollLeaveSnapshot $payrollLeaveSnapshot): bool
    {
        return $authUser->can('Replicate:PayrollLeaveSnapshot');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:PayrollLeaveSnapshot');
    }

    public function viewOwnTerritory(AuthUser $authUser, PayrollLeaveSnapshot $payrollLeaveSnapshot): bool
    {
        return $authUser->can('ViewOwnTerritory:PayrollLeaveSnapshot');
    }

    public function viewOwnOU(AuthUser $authUser, PayrollLeaveSnapshot $payrollLeaveSnapshot): bool
    {
        return $authUser->can('ViewOwnOU:PayrollLeaveSnapshot');
    }

    public function viewOwn(AuthUser $authUser, PayrollLeaveSnapshot $payrollLeaveSnapshot): bool
    {
        return $authUser->can('ViewOwn:PayrollLeaveSnapshot');
    }

}