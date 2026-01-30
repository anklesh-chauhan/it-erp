<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use App\Services\PositionService;
use App\Services\OrganizationalUnitService;
use Illuminate\Support\Facades\Auth;

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

        $table = $query->getModel()->getTable();

        $query->where(function (Builder $q) use ($user, $model, $table) {

            /* =====================================================
            | A. ALWAYS SHOW OWN RECORDS
            ===================================================== */
            if (\Schema::hasColumn($table, 'created_by')) {
                $q->orWhere($table . '.created_by', $user->id);
            }

            if (\Schema::hasColumn($table, 'user_id')) {
                $q->orWhere($table . '.user_id', $user->id);
            }

            if (\Schema::hasColumn($table, 'login_id')) {
                $q->orWhere($table . '.login_id', $user->id);
            }

            /* =====================================================
            | B. VIEW OWN TERRITORY
            ===================================================== */
            if ($user->can("ViewOwnTerritory:{$model}")) {

                $territoryIds = PositionService::getTerritoryIdsForUser($user);

                if (! empty($territoryIds)) {

                    if (method_exists($q->getModel(), 'scopeApplyTerritoryVisibility')) {
                        $q->orWhere(function ($tq) use ($territoryIds) {
                            $tq->applyTerritoryVisibility($territoryIds);
                        });
                    } elseif (\Schema::hasColumn($table, 'territory_id')) {
                        $q->orWhereIn($table . '.territory_id', $territoryIds);
                    }
                }
            }

            /* =====================================================
            | C. VIEW OWN OU
            ===================================================== */
            if ($user->can("ViewOwnOU:{$model}")) {

                $ouIds = OrganizationalUnitService::getUserOuIds($user);

                if (! empty($ouIds)) {
                    $q->orWhereHas(
                        'creator.employee.employmentDetail.organizationalUnits',
                        fn ($ou) => $ou->whereIn('organizational_units.id', $ouIds)
                    );
                }
            }

            /* =====================================================
            | D. VIEW OWN (MODEL-DEFINED)
            ===================================================== */
            if ($user->can("ViewOwn:{$model}")) {

                if (method_exists($q->getModel(), 'scopeApplyOwnVisibility')) {
                    $q->orWhere(function ($oq) use ($user) {
                        $oq->applyOwnVisibility($user);
                    });
                }
            }
        });

        return $query;
    }
}
