<?php

use App\Models\SalesTourPlan;
use App\Models\SalesTourPlanDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

it('lists the authenticated users tour plan for today', function () {
    $user = reportingUser('TourApi');
    $territory = reportingTerritory('Tour Terr');
    $patch = reportingPatch($territory, 'Tour Patch');
    $doctor = reportingDoctor($user, 'Dr Tour');
    $patch->companies()->attach($doctor->id);

    $this->actingAs($user);

    $plan = SalesTourPlan::forceCreate([
        'user_id' => $user->id,
        'month' => now()->format('Y-m'),
        'approval_status' => 'approved',
        'created_by' => $user->id,
        'updated_by' => $user->id,
    ]);

    $detail = SalesTourPlanDetail::forceCreate([
        'sales_tour_plan_id' => $plan->id,
        'date' => today(),
        'territory_id' => $territory->id,
        'created_by' => $user->id,
        'updated_by' => $user->id,
    ]);

    $detail->patches()->attach($patch->id);

    Sanctum::actingAs($user);

    $this->getJson('/api/v1/todays-tour')
        ->assertSuccessful()
        ->assertJsonPath('data.0.id', $detail->id)
        ->assertJsonPath('data.0.territory.name', 'Tour Terr')
        ->assertJsonPath('data.0.patches.0.companies.0.name', 'Dr Tour');
});

it('hides another users tour plan', function () {
    $owner = reportingUser('TourOwner');
    $other = reportingUser('TourOther');
    $territory = reportingTerritory('Hidden Terr');

    $this->actingAs($owner);

    $plan = SalesTourPlan::forceCreate([
        'user_id' => $owner->id,
        'month' => now()->format('Y-m'),
        'approval_status' => 'approved',
        'created_by' => $owner->id,
        'updated_by' => $owner->id,
    ]);

    SalesTourPlanDetail::forceCreate([
        'sales_tour_plan_id' => $plan->id,
        'date' => today(),
        'territory_id' => $territory->id,
        'created_by' => $owner->id,
        'updated_by' => $owner->id,
    ]);

    Sanctum::actingAs($other);

    $this->getJson('/api/v1/todays-tour')
        ->assertSuccessful()
        ->assertJsonCount(0, 'data');
});
