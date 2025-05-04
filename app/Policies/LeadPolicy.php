<?php

namespace App\Policies;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeadPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view_any_lead') && $user->hasAnyRole(['admin', 'general_manager', 'marketing_manager']);
    }

    public function view(User $user, Lead $lead): bool
    {
        return $user->hasPermissionTo('view_lead') && $user->hasAnyRole(['admin', 'general_manager', 'marketing_manager']);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create_lead') && $user->hasAnyRole(['admin', 'general_manager', 'marketing_manager']);
    }

    public function update(User $user, Lead $lead): bool
    {
        return $user->hasPermissionTo('update_lead') && $user->hasAnyRole(['admin', 'general_manager', 'marketing_manager']);
    }

    public function delete(User $user, Lead $lead): bool
    {
        return $user->hasPermissionTo('delete_lead') && $user->hasAnyRole(['admin', 'general_manager', 'marketing_manager']);
    }

    public function restore(User $user, Lead $lead): bool
    {
        return $user->hasPermissionTo('restore_lead') && $user->hasAnyRole(['admin', 'general_manager', 'marketing_manager']);
    }

    public function forceDelete(User $user, Lead $lead): bool
    {
        return $user->hasPermissionTo('force_delete_lead') && $user->hasAnyRole(['admin', 'general_manager', 'marketing_manager']);
    }
}
