<?php

namespace App\Services;

use App\Models\User;
use App\Models\Position;
use Illuminate\Support\Collection;

class PositionService
{
    public static function getTerritoryIdsForUser($user): array
    {
        return $user->employee
            ?->positions
            ?->flatMap(fn ($position) => $position->territories)
            ?->pluck('id')
            ?->unique()
            ?->values()
            ?->toArray() ?? [];
    }

    public static function getVisibleUserIdsFor(User $user): Collection
    {
        $employee = $user->employee;

        if (! $employee) {
            return collect([$user->id]);
        }

        $primaryPosition = $employee->positions()
            ->wherePivot('is_primary', true)
            ->first();

        if (! $primaryPosition) {
            return collect([$user->id]);
        }

        // Get all child positions recursively
        $positionIds = self::getDescendantPositionIds($primaryPosition);

        // Include own position
        $positionIds->push($primaryPosition->id);

        return User::whereHas('employee.positions', function ($q) use ($positionIds) {
            $q->whereIn('positions.id', $positionIds);
        })->pluck('id');
    }

    protected static function getDescendantPositionIds(Position $position): Collection
    {
        $ids = collect();

        foreach ($position->subordinates as $child) {
            $ids->push($child->id);
            $ids = $ids->merge(self::getDescendantPositionIds($child));
        }

        return $ids;
    }
}
