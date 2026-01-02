<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use App\Services\PositionService;
use App\Services\OrganizationalUnitService;

trait HasVisibilityScope
{
    public function scopeApplyVisibility(Builder $query, string $model): Builder
    {
        $user = auth()->user();

        if (! $user) {
            return $query->whereRaw('1 = 0');
        }

        /* =====================================================
         | 1. SUPER / FULL ACCESS
         ===================================================== */
        if (
            $user->hasRole('super_admin') ||
            $user->hasRole('administration_admin') ||
            $user->can('AccessAllRecords')
        ) {
            return $query;
        }

        /* =====================================================
         | 2. VIEW OWN TERRITORY (PRIMARY FILTER)
         ===================================================== */
        if ($user->can("ViewOwnTerritory:{$model}")) {

            $territoryIds = PositionService::getTerritoryIdsForUser($user);

            if (
                empty($territoryIds) ||
                ! \Schema::hasColumn($query->getModel()->getTable(), 'territory_id')
            ) {
                return $query->whereRaw('1 = 0');
            }
            $query = $query->whereIn(
                $query->getModel()->getTable() . '.territory_id',
                $territoryIds
            );

            return $query->whereIn(
                $query->getModel()->getTable() . '.territory_id',
                $territoryIds
            );
        }

        /* =====================================================
         | VIEW OWN OU (STRUCTURAL)
         ===================================================== */
        if ($user->can("ViewOwnOU:{$model}")) {

            $ouIds = OrganizationalUnitService::getUserOuIds($user);

            if (empty($ouIds)) {
                return $query->whereRaw('1 = 0');
            }

            return $query->whereHas(
                'creator.employee.employmentDetail.organizationalUnits',
                fn ($q) => $q->whereIn('organizational_units.id', $ouIds)
            );
        }

        /* =====================================================
         | 3. VIEW OWN RECORDS
         ===================================================== */
        if ($user->can("ViewOwn:{$model}")) {
            return $query->where(
                $query->getModel()->getTable() . '.created_by',
                $user->id
            );
        }

        /* =====================================================
         | 4. DEFAULT: NO DATA
         ===================================================== */
        return $query->whereRaw('1 = 0');
    }
}
