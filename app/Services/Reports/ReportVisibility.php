<?php

namespace App\Services\Reports;

use App\Models\User;
use App\Services\PositionService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ReportVisibility
{
    public static function hasFullAccess(?User $user): bool
    {
        if ($user === null) {
            return false;
        }

        return $user->hasRole('super_admin')
            || $user->hasRole('administration_admin')
            || $user->can('AccessAllRecords');
    }

    public static function constrainToVisibleUsers(Builder $query, string $userIdColumn = 'user_id'): Builder
    {
        $user = Auth::user();

        if ($user === null) {
            return $query->whereRaw('1 = 0');
        }

        if (self::hasFullAccess($user)) {
            return $query;
        }

        $visibleUserIds = PositionService::getVisibleUserIdsFor($user);

        if ($visibleUserIds->isEmpty()) {
            return $query->whereRaw('1 = 0');
        }

        return $query->whereIn($userIdColumn, $visibleUserIds->all());
    }

    public static function constrainToVisibleEmployees(Builder $query, string $relation = 'employee'): Builder
    {
        $user = Auth::user();

        if ($user === null) {
            return $query->whereRaw('1 = 0');
        }

        if (self::hasFullAccess($user)) {
            return $query;
        }

        $visibleUserIds = PositionService::getVisibleUserIdsFor($user);

        if ($visibleUserIds->isEmpty()) {
            return $query->whereRaw('1 = 0');
        }

        return $query->whereHas(
            $relation,
            fn (Builder $employeeQuery): Builder => $employeeQuery->whereIn('login_id', $visibleUserIds->all())
        );
    }
}
