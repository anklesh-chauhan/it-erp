<?php

use App\Models\Visit;
use App\Models\VisitPreference;
use App\Services\Visit\VisitEnforcementService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->actingAs(reportingUser('PrefUser'));
});

it('requires GPS when check-in GPS is enforced', function () {
    VisitPreference::current()->update([
        'enable_check_in' => true,
        'require_gps' => true,
    ]);

    expect(fn () => app(VisitEnforcementService::class)->validateCheckIn(null, null))
        ->toThrow(ValidationException::class);
});

it('allows check-in when GPS is provided', function () {
    VisitPreference::current()->update([
        'enable_check_in' => true,
        'require_gps' => true,
    ]);

    app(VisitEnforcementService::class)->validateCheckIn(19.0760, 72.8777);

    expect(true)->toBeTrue();
});

it('requires check-in before check-out when that rule is on', function () {
    $territory = reportingTerritory('Enforce');
    $patch = reportingPatch($territory, 'Enforce Patch');
    $rep = reportingUser('EnforceRep');

    VisitPreference::current()->update([
        'enable_check_out' => true,
        'enforce_check_in_before_check_out' => true,
        'require_gps' => false,
    ]);

    $visit = Visit::withoutEvents(fn (): Visit => Visit::query()->create([
        'document_number' => 'VIS-ENF-'.fake()->unique()->numerify('####'),
        'employee_id' => $rep->id,
        'territory_id' => $territory->id,
        'patch_id' => $patch->id,
        'visit_date' => today(),
        'visit_type' => 'unplanned',
        'visit_status' => 'draft',
        'approval_status' => 'draft',
        'created_by' => $rep->id,
        'updated_by' => $rep->id,
    ]));

    expect(fn () => app(VisitEnforcementService::class)->validateCheckOut($visit, 19.07, 72.87))
        ->toThrow(ValidationException::class);
});
