<?php

use App\Models\SgipDistribution;
use App\Models\SgipDistributionItem;
use App\Models\SgipLimit;
use App\Models\SgipViolation;
use App\Services\SGIPComplianceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);

it('blocks a distribution that exceeds a global quantity limit', function () {
    $rep = reportingUser('SgipRep');
    $territory = reportingTerritory('Sgip North');
    $doctor = reportingDoctor($rep, 'Limit Doctor');
    $item = reportingSampleItem('SGL', 'Limited Sample');
    $item->forceFill(['category_type' => 'sample'])->save();

    $this->actingAs($rep);

    SgipLimit::query()->create([
        'applies_to' => 'global',
        'item_type' => 'sample',
        'period' => 'daily',
        'max_quantity' => 2,
        'created_by' => $rep->id,
        'updated_by' => $rep->id,
    ]);

    $distribution = SgipDistribution::query()->create([
        'user_id' => $rep->id,
        'employee_id' => $rep->employee_id,
        'account_master_id' => $doctor->id,
        'territory_id' => $territory->id,
        'visit_date' => today(),
        'approval_status' => 'draft',
        'created_by' => $rep->id,
        'updated_by' => $rep->id,
    ]);

    SgipDistributionItem::query()->create([
        'sgip_distribution_id' => $distribution->id,
        'item_master_id' => $item->id,
        'quantity' => 5,
        'unit_value' => 10,
        'created_by' => $rep->id,
        'updated_by' => $rep->id,
    ]);

    SGIPComplianceService::validate($distribution->fresh(), false);

    expect(SgipViolation::query()->where('sgip_distribution_id', $distribution->id)->count())->toBeGreaterThan(0);

    expect(fn () => SGIPComplianceService::validate($distribution->fresh(), true))
        ->toThrow(ValidationException::class);
});

it('rejects a duplicate limit combination', function () {
    $rep = reportingUser('SgipDup');

    $this->actingAs($rep);

    $payload = [
        'applies_to' => 'global',
        'item_type' => 'gift',
        'period' => 'monthly',
        'max_quantity' => 1,
        'created_by' => $rep->id,
        'updated_by' => $rep->id,
    ];

    SgipLimit::query()->create($payload);

    expect(fn () => SgipLimit::query()->create($payload))
        ->toThrow(InvalidArgumentException::class);
});
