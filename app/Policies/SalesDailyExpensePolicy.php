<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\SalesDailyExpense;
use Illuminate\Auth\Access\HandlesAuthorization;

class SalesDailyExpensePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:SalesDailyExpense');
    }

    public function view(AuthUser $authUser, SalesDailyExpense $salesDailyExpense): bool
    {
        return $authUser->can('View:SalesDailyExpense');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:SalesDailyExpense');
    }

    public function update(AuthUser $authUser, SalesDailyExpense $salesDailyExpense): bool
    {
        return $authUser->can('Update:SalesDailyExpense');
    }

    public function delete(AuthUser $authUser, SalesDailyExpense $salesDailyExpense): bool
    {
        return $authUser->can('Delete:SalesDailyExpense');
    }

    public function restore(AuthUser $authUser, SalesDailyExpense $salesDailyExpense): bool
    {
        return $authUser->can('Restore:SalesDailyExpense');
    }

    public function forceDelete(AuthUser $authUser, SalesDailyExpense $salesDailyExpense): bool
    {
        return $authUser->can('ForceDelete:SalesDailyExpense');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:SalesDailyExpense');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:SalesDailyExpense');
    }

    public function replicate(AuthUser $authUser, SalesDailyExpense $salesDailyExpense): bool
    {
        return $authUser->can('Replicate:SalesDailyExpense');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:SalesDailyExpense');
    }

    public function viewOwnTerritory(AuthUser $authUser, SalesDailyExpense $salesDailyExpense): bool
    {
        return $authUser->can('ViewOwnTerritory:SalesDailyExpense');
    }

    public function viewOwnOU(AuthUser $authUser, SalesDailyExpense $salesDailyExpense): bool
    {
        return $authUser->can('ViewOwnOU:SalesDailyExpense');
    }

    public function viewOwn(AuthUser $authUser, SalesDailyExpense $salesDailyExpense): bool
    {
        return $authUser->can('ViewOwn:SalesDailyExpense');
    }

    public function overrideApproval(AuthUser $authUser, SalesDailyExpense $salesDailyExpense): bool
    {
        return $authUser->can('OverrideApproval:SalesDailyExpense');
    }

}