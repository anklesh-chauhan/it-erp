<?php

use App\Models\AccountMaster;
use App\Models\Patch;
use App\Models\SalesTourPlanDetail;
use App\Models\Visit;
use App\Models\VisitableVisit;
use App\Services\Reports\VisitCoverageCalculator;

it('treats completed planned accounts as covered', function () {
    $account = (new AccountMaster)->forceFill(['id' => 11]);
    $patch = new Patch;
    $patch->setRelation('companies', collect([$account]));

    $link = (new VisitableVisit)->forceFill([
        'visitable_type' => AccountMaster::class,
        'visitable_id' => 11,
    ]);

    $visit = (new Visit)->forceFill(['visit_status' => 'completed']);
    $visit->setRelation('visitables', collect([$link]));

    $detail = new SalesTourPlanDetail;
    $detail->setRelation('patches', collect([$patch]));
    $detail->setRelation('visits', collect([$visit]));

    expect((new VisitCoverageCalculator)->forDetail($detail))->toMatchArray([
        'planned' => 1,
        'completed' => 1,
        'visits' => 1,
        'percentage' => 100.0,
    ]);
});

it('does not count unplanned completed visits toward coverage', function () {
    $plannedAccount = (new AccountMaster)->forceFill(['id' => 11]);
    $patch = new Patch;
    $patch->setRelation('companies', collect([$plannedAccount]));

    $link = (new VisitableVisit)->forceFill([
        'visitable_type' => AccountMaster::class,
        'visitable_id' => 99,
    ]);

    $visit = (new Visit)->forceFill(['visit_status' => 'completed']);
    $visit->setRelation('visitables', collect([$link]));

    $detail = new SalesTourPlanDetail;
    $detail->setRelation('patches', collect([$patch]));
    $detail->setRelation('visits', collect([$visit]));

    expect((new VisitCoverageCalculator)->forDetail($detail))->toMatchArray([
        'planned' => 1,
        'completed' => 0,
        'visits' => 1,
        'percentage' => 0.0,
    ]);
});

it('returns zero coverage when no accounts are planned', function () {
    $detail = new SalesTourPlanDetail;
    $detail->setRelation('patches', collect());
    $detail->setRelation('visits', collect());

    expect((new VisitCoverageCalculator)->forDetail($detail)['percentage'])->toBe(0.0);
});
