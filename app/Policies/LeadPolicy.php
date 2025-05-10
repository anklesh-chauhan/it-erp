<?php

namespace App\Policies;

use App\Models\Lead;
use App\Models\User;
use App\Models\TenantUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeadPolicy
{
    use HandlesAuthorization;

    public function viewAny(User|TenantUser $user): bool
    {
        return $user->hasPermissionTo('view_any_lead');
    }

    public function view(User|TenantUser $user, Lead $lead): bool
    {
        return $user->hasPermissionTo('view_lead');
    }

    public function create(User|TenantUser $user): bool
    {
        return $user->hasPermissionTo('create_lead');
    }

    public function update(User|TenantUser $user, Lead $lead): bool
    {
        return $user->hasPermissionTo('update_lead');
    }

    public function delete(User|TenantUser $user, Lead $lead): bool
    {
        return $user->hasPermissionTo('delete_lead');
    }

    public function restore(User|TenantUser $user, Lead $lead): bool
    {
        return $user->hasPermissionTo('restore_lead');
    }

    public function forceDelete(User|TenantUser $user, Lead $lead): bool
    {
        return $user->hasPermissionTo('force_delete_lead');
    }
}
