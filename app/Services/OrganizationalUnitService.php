<?php

namespace App\Services;

use App\Models\OrganizationalUnit;

class OrganizationalUnitService
{
    public static function getUserOuIds($user): array
    {
        $primaryOuIds = $user->employee
            ?->employmentDetail
            ?->organizationalUnits
            ?->pluck('organizational_units.id')
            ?->toArray() ?? [];

        if (empty($primaryOuIds)) {
            return [];
        }

        return OrganizationalUnit::whereIn('id', $primaryOuIds)
            ->orWhereIn('parent_id', $primaryOuIds)
            ->pluck('id')
            ->toArray();
    }
}
