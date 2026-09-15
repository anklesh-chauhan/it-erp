<?php

namespace App\Services\Reports;

final readonly class DashboardMetricsResult
{
    public function __construct(
        public int $visitsToday,
        public int $pendingDcrs,
        public int $pendingApprovals,
        public int $punchedInToday,
    ) {}

    public static function empty(): self
    {
        return new self(0, 0, 0, 0);
    }
}
