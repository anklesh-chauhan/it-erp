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

            if (empty($territoryIds)) {
                return $query->whereRaw('1 = 0');
            }

            // Model-specific territory scope
            if (method_exists($query->getModel(), 'scopeApplyTerritoryVisibility')) {

                return $query->applyTerritoryVisibility($territoryIds);
            }

            // $query = $query->whereIn(
            //     $query->getModel()->getTable() . '.territory_id',
            //     $territoryIds
            // );

            if (\Schema::hasColumn($query->getModel()->getTable(), 'territory_id')) {
                return $query->whereIn(
                    $query->getModel()->getTable() . '.territory_id',
                    $territoryIds
                );
            }

            // return $query->whereIn(
            //     $query->getModel()->getTable() . '.territory_id',
            //     $territoryIds
            // );
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



            $table = $query->getModel()->getTable();

            // SalesTourPlan & similar owner-based models
            if (\Schema::hasColumn($table, 'user_id')) {
                return $query->where($table . '.user_id', $user->id);
            }

            // Standard created_by ownership
            if (\Schema::hasColumn($table, 'created_by')) {
                return $query->where($table . '.created_by', $user->id);
            }

            return $query->whereRaw('1 = 0');
        }

        /* =====================================================
         | 4. DEFAULT: NO DATA
         ===================================================== */
        return $query->whereRaw('1 = 0');
    }
}
