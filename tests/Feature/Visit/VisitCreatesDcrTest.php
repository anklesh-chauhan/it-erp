<?php

use App\Models\SalesDcr;
use App\Models\Visit;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('attaches a draft DCR when a visit is created', function () {
    $territory = reportingTerritory('Dcr West');
    $patch = reportingPatch($territory, 'Dcr Patch');
    $rep = reportingUser('VisitDcrRep');

    $this->actingAs($rep);

    $visit = Visit::query()->create([
        'document_number' => 'VIS-DCR-'.fake()->unique()->numerify('####'),
        'employee_id' => $rep->id,
        'territory_id' => $territory->id,
        'patch_id' => $patch->id,
        'visit_date' => today(),
        'visit_type' => 'unplanned',
        'visit_status' => 'draft',
        'approval_status' => 'draft',
        'created_by' => $rep->id,
        'updated_by' => $rep->id,
    ]);

    expect($visit->sales_dcr_id)->not->toBeNull();

    $dcr = SalesDcr::query()->findOrFail($visit->sales_dcr_id);

    expect($dcr->user_id)->toBe($rep->id)
        ->and($dcr->dcr_date->toDateString())->toBe(today()->toDateString())
        ->and($dcr->approval_status)->toBe('draft')
        ->and((float) $dcr->fresh()->visits_count)->toBe(1.0);
});

it('moves the visit to another DCR when the visit date changes', function () {
    $territory = reportingTerritory('Dcr Move');
    $patch = reportingPatch($territory, 'Move Patch');
    $rep = reportingUser('VisitMoveRep');

    $this->actingAs($rep);

    $visit = Visit::query()->create([
        'document_number' => 'VIS-MOVE-'.fake()->unique()->numerify('####'),
        'employee_id' => $rep->id,
        'territory_id' => $territory->id,
        'patch_id' => $patch->id,
        'visit_date' => today(),
        'visit_type' => 'unplanned',
        'visit_status' => 'draft',
        'approval_status' => 'draft',
        'created_by' => $rep->id,
        'updated_by' => $rep->id,
    ]);

    $originalDcrId = (int) $visit->sales_dcr_id;

    $visit->update(['visit_date' => today()->addDay()->toDateString()]);

    $visit = $visit->fresh();

    expect((int) $visit->sales_dcr_id)->not->toBe($originalDcrId)
        ->and((float) SalesDcr::query()->find($originalDcrId)?->visits_count)->toBe(0.0)
        ->and((float) $visit->salesDcr->visits_count)->toBe(1.0);
});
