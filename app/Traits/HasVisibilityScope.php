<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use App\Services\TerritoryService;
use App\Services\OrganizationalUnitService;
use Illuminate\Support\Facades\Auth;

trait HasVisibilityScope
{
    public function scopeApplyVisibility(Builder $query, string $model): Builder
    {
        $user = auth()->user();

        if (! $user) {
            return $query;
        }

        /* ========== ADMIN (FULL ACCESS) ========== */
        if ($user->hasRole('super_admin') || $user->hasRole('administration_admin')) {
            return $query;
        }

        // Access without ViewOwn
        if ($user->can("ViewAny:{$model}") && ! $user->can("ViewOwn:{$model}")) {
            return $query;
        }

        /* ========== OWN RECORDS ONLY ========== */
        if ($user->can("ViewOwn:{$model}") && ! $user->can("ViewAny:{$model}")) {
            return $query->where('created_by', $user->id);
        }

        /* ========== OU + TERRITORY SCOPE ========== */
        if ($user->can("ViewAny:{$model}")) {
            return $this->applyOuAndTerritoryScope($query, $user);
        }

        return $query->whereRaw('1 = 0');
    }

    protected function applyOuAndTerritoryScope(Builder $query, $user): Builder
    {
        $ouIds        = OrganizationalUnitService::getUserOuIds($user);
        $territoryIds = TerritoryService::getUserTerritoryIds($user);

        return $query
            // 🔹 OU scope via creator → employee → employmentDetail → organizationalUnits
            ->whereHas('creator.employee.employmentDetail.organizationalUnits', function ($q) use ($ouIds) {
                $q->whereIn('organizational_units.id', $ouIds);
            })
            // 🔹 Territory scope (if model has territory_id)
            ->when(
                $territoryIds && \Schema::hasColumn($query->getModel()->getTable(), 'territory_id'),
                fn ($q) => $q->whereIn('territory_id', $territoryIds)
            );
    }
}
