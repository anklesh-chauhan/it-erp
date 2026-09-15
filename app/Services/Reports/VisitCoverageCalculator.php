<?php

namespace App\Services\Reports;

use App\Models\AccountMaster;
use App\Models\Patch;
use App\Models\SalesTourPlanDetail;
use App\Models\Visit;

class VisitCoverageCalculator
{
    /**
     * @var array<int, array{planned: int, completed: int, visits: int, percentage: float}>
     */
    private array $cache = [];

    /**
     * @return array{planned: int, completed: int, visits: int, percentage: float}
     */
    public function forDetail(SalesTourPlanDetail $detail): array
    {
        $key = (int) ($detail->getKey() ?: spl_object_id($detail));

        if (isset($this->cache[$key])) {
            return $this->cache[$key];
        }

        $planned = $detail->patches
            ->flatMap(fn (Patch $patch) => $patch->companies->pluck('id'))
            ->unique()
            ->values();

        $completed = $detail->visits
            ->where('visit_status', 'completed')
            ->flatMap(function (Visit $visit) {
                return $visit->visitables
                    ->where('visitable_type', AccountMaster::class)
                    ->pluck('visitable_id');
            })
            ->unique()
            ->intersect($planned)
            ->values();

        $plannedCount = $planned->count();
        $completedCount = $completed->count();

        return $this->cache[$key] = [
            'planned' => $plannedCount,
            'completed' => $completedCount,
            'visits' => $detail->visits->count(),
            'percentage' => $plannedCount === 0
                ? 0.0
                : round(($completedCount / $plannedCount) * 100, 1),
        ];
    }
}
