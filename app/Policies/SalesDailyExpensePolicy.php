<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SalesDailyExpense;
use Illuminate\Auth\Access\HandlesAuthorization;

class SalesDailyExpensePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_sales::daily::expense');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SalesDailyExpense $salesDailyExpense): bool
    {
        return $user->can('view_sales::daily::expense');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_sales::daily::expense');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SalesDailyExpense $salesDailyExpense): bool
    {
        return $user->can('update_sales::daily::expense');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SalesDailyExpense $salesDailyExpense): bool
    {
        return $user->can('delete_sales::daily::expense');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_sales::daily::expense');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, SalesDailyExpense $salesDailyExpense): bool
    {
        return $user->can('force_delete_sales::daily::expense');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_sales::daily::expense');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, SalesDailyExpense $salesDailyExpense): bool
    {
        return $user->can('restore_sales::daily::expense');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_sales::daily::expense');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, SalesDailyExpense $salesDailyExpense): bool
    {
        return $user->can('replicate_sales::daily::expense');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_sales::daily::expense');
    }
}
