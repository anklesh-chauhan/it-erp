<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ExpenseConfiguration;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExpenseConfigurationPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ExpenseConfiguration');
    }

    public function view(AuthUser $authUser, ExpenseConfiguration $expenseConfiguration): bool
    {
        return $authUser->can('View:ExpenseConfiguration');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ExpenseConfiguration');
    }

    public function update(AuthUser $authUser, ExpenseConfiguration $expenseConfiguration): bool
    {
        return $authUser->can('Update:ExpenseConfiguration');
    }

    public function delete(AuthUser $authUser, ExpenseConfiguration $expenseConfiguration): bool
    {
        return $authUser->can('Delete:ExpenseConfiguration');
    }

    public function restore(AuthUser $authUser, ExpenseConfiguration $expenseConfiguration): bool
    {
        return $authUser->can('Restore:ExpenseConfiguration');
    }

    public function forceDelete(AuthUser $authUser, ExpenseConfiguration $expenseConfiguration): bool
    {
        return $authUser->can('ForceDelete:ExpenseConfiguration');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ExpenseConfiguration');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ExpenseConfiguration');
    }

    public function replicate(AuthUser $authUser, ExpenseConfiguration $expenseConfiguration): bool
    {
        return $authUser->can('Replicate:ExpenseConfiguration');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ExpenseConfiguration');
    }

    public function viewOwnTerritory(AuthUser $authUser, ExpenseConfiguration $expenseConfiguration): bool
    {
        return $authUser->can('ViewOwnTerritory:ExpenseConfiguration');
    }

    public function viewOwn(AuthUser $authUser, ExpenseConfiguration $expenseConfiguration): bool
    {
        return $authUser->can('ViewOwn:ExpenseConfiguration');
    }

}