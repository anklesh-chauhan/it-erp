<?php

namespace App\Services;

use App\Models\User;
use App\Models\Position;

class JointUserService
{
    public static function getJointUsersForUser(User $user)
    {
        // 🔓 Super Admin / Full access
        if (
            $user->hasRole('super_admin') ||
            $user->can('AccessAllRecords')
        ) {
            return User::query()
                ->where('id', '!=', $user->id);
        }

        $employee = $user->employee;
        $employmentDetail = $employee?->employmentDetail;

        if (! $employmentDetail) {
            return User::whereRaw('1 = 0');
        }

        /**
         * Resolve user's positions
         * (employee_position_pivot supports multiple positions)
         */
        $positionIds = $employee->positions()->pluck('positions.id')->toArray();

        if (empty($positionIds)) {
            return User::whereRaw('1 = 0');
        }

        /**
         * Collect:
         * 1. Same positions
         * 2. Parent positions (reports_to)
         * 3. Child positions (subordinates)
         */
        $relatedPositionIds = Position::query()
            ->whereIn('id', $positionIds)
            ->orWhereIn('reports_to_position_id', $positionIds)
            ->orWhereIn('id', function ($q) use ($positionIds) {
                $q->select('reports_to_position_id')
                  ->from('positions')
                  ->whereIn('id', $positionIds)
                  ->whereNotNull('reports_to_position_id');
            })
            ->pluck('id')
            ->unique()
            ->toArray();

        /**
         * Users whose employee has ANY of these positions
         */
        return User::query()
            ->where('id', '!=', $user->id)
            ->whereHas('employee.positions', function ($q) use ($relatedPositionIds) {
                $q->whereIn('positions.id', $relatedPositionIds);
            });
    }
}
