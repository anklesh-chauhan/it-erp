<?php

use App\Models\FieldGpsPoint;
use App\Models\Visit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

it('stores gps points for the authenticated user', function () {
    $user = reportingUser('GpsApi');

    Sanctum::actingAs($user);

    $clientUuid = fake()->uuid();

    $this->postJson('/api/v1/gps-points', [
        'source' => 'flutter',
        'device_id' => 'pixel-8',
        'points' => [
            [
                'latitude' => 19.0760,
                'longitude' => 72.8777,
                'recorded_at' => now()->subMinutes(5)->toIso8601String(),
                'accuracy_meters' => 12.5,
                'client_uuid' => $clientUuid,
            ],
            [
                'latitude' => 19.0770,
                'longitude' => 72.8780,
                'recorded_at' => now()->subMinutes(2)->toIso8601String(),
                'accuracy_meters' => 8.0,
            ],
        ],
    ])
        ->assertSuccessful()
        ->assertJsonCount(2, 'data');

    expect(FieldGpsPoint::query()->where('user_id', $user->id)->count())->toBe(2);
});

it('is idempotent for the same client_uuid', function () {
    $user = reportingUser('GpsIdempotent');

    Sanctum::actingAs($user);

    $payload = [
        'points' => [
            [
                'latitude' => 19.0760,
                'longitude' => 72.8777,
                'recorded_at' => now()->toIso8601String(),
                'client_uuid' => '11111111-1111-1111-1111-111111111111',
            ],
        ],
    ];

    $this->postJson('/api/v1/gps-points', $payload)->assertSuccessful();
    $this->postJson('/api/v1/gps-points', $payload)->assertSuccessful();

    expect(FieldGpsPoint::query()->where('user_id', $user->id)->count())->toBe(1);
});

it('rejects gps points for another users visit', function () {
    $owner = reportingUser('GpsVisitOwner');
    $other = reportingUser('GpsVisitOther');
    $territory = reportingTerritory('Gps Terr');
    $patch = reportingPatch($territory, 'Gps Patch');

    $this->actingAs($owner);

    $visit = Visit::withoutEvents(fn (): Visit => Visit::query()->create([
        'document_number' => 'VIS-GPS-'.fake()->unique()->numerify('####'),
        'employee_id' => $owner->id,
        'territory_id' => $territory->id,
        'patch_id' => $patch->id,
        'visit_date' => today(),
        'visit_type' => 'unplanned',
        'visit_status' => 'started',
        'approval_status' => 'draft',
        'created_by' => $owner->id,
        'updated_by' => $owner->id,
    ]));

    Sanctum::actingAs($other);

    $this->postJson('/api/v1/gps-points', [
        'points' => [
            [
                'latitude' => 19.0760,
                'longitude' => 72.8777,
                'recorded_at' => now()->toIso8601String(),
                'visit_id' => $visit->id,
            ],
        ],
    ])->assertUnprocessable();
});
