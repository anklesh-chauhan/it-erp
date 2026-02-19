<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\SalesDcrExpense;
use Illuminate\Auth\Access\HandlesAuthorization;

class SalesDcrExpensePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:SalesDcrExpense');
    }

    public function view(AuthUser $authUser, SalesDcrExpense $salesDcrExpense): bool
    {
        return $authUser->can('View:SalesDcrExpense');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:SalesDcrExpense');
    }

    public function update(AuthUser $authUser, SalesDcrExpense $salesDcrExpense): bool
    {
        return $authUser->can('Update:SalesDcrExpense');
    }

    public function delete(AuthUser $authUser, SalesDcrExpense $salesDcrExpense): bool
    {
        return $authUser->can('Delete:SalesDcrExpense');
    }

    public function restore(AuthUser $authUser, SalesDcrExpense $salesDcrExpense): bool
    {
        return $authUser->can('Restore:SalesDcrExpense');
    }

    public function forceDelete(AuthUser $authUser, SalesDcrExpense $salesDcrExpense): bool
    {
        return $authUser->can('ForceDelete:SalesDcrExpense');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:SalesDcrExpense');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:SalesDcrExpense');
    }

    public function replicate(AuthUser $authUser, SalesDcrExpense $salesDcrExpense): bool
    {
        return $authUser->can('Replicate:SalesDcrExpense');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:SalesDcrExpense');
    }

    public function viewOwnTerritory(AuthUser $authUser, SalesDcrExpense $salesDcrExpense): bool
    {
        return $authUser->can('ViewOwnTerritory:SalesDcrExpense');
    }

    public function viewOwnOU(AuthUser $authUser, SalesDcrExpense $salesDcrExpense): bool
    {
        return $authUser->can('ViewOwnOU:SalesDcrExpense');
    }

    public function viewOwn(AuthUser $authUser, SalesDcrExpense $salesDcrExpense): bool
    {
        return $authUser->can('ViewOwn:SalesDcrExpense');
    }

    public function overrideApproval(AuthUser $authUser, SalesDcrExpense $salesDcrExpense): bool
    {
        return $authUser->can('OverrideApproval:SalesDcrExpense');
    }

}