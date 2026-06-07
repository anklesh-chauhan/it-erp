<?php

use App\Models\CityPinCode;
use App\Models\StandardFareChart;
use App\Services\Travel\GoogleRoutesService;
use App\Services\Travel\SfcDistanceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

beforeEach(function () {
    Config::set('services.google_maps.api_key', 'test-api-key');
});

test('google routes service computes driving distance between geocoded area towns', function () {
    $from = CityPinCode::query()->create([
        'pin_code' => 380001,
        'area_town' => 'Ranip',
    ]);

    $to = CityPinCode::query()->create([
        'pin_code' => 380015,
        'area_town' => 'Satellite',
    ]);

    Http::fake([
        'maps.googleapis.com/*' => Http::response([
            'results' => [
                [
                    'geometry' => [
                        'location' => [
                            'lat' => 23.0700,
                            'lng' => 72.5700,
                        ],
                    ],
                ],
            ],
        ]),
        'routes.googleapis.com/*' => Http::response([
            'routes' => [
                ['distanceMeters' => 12500],
            ],
        ]),
    ]);

    $distance = app(GoogleRoutesService::class)->computeDistanceBetweenAreaTowns($from, $to);

    expect($distance)->toBe(12.5);
    expect((float) $from->fresh()->latitude)->toBe(23.07);
    expect((float) $from->fresh()->longitude)->toBe(72.57);
});

test('google routes service reuses stored coordinates', function () {
    $from = CityPinCode::query()->create([
        'pin_code' => 110001,
        'area_town' => 'Connaught Place',
        'latitude' => 28.6315,
        'longitude' => 77.2167,
    ]);

    $to = CityPinCode::query()->create([
        'pin_code' => 110020,
        'area_town' => 'Saket',
        'latitude' => 28.5245,
        'longitude' => 77.2066,
    ]);

    Http::fake([
        'routes.googleapis.com/*' => Http::response([
            'routes' => [
                ['distanceMeters' => 8400],
            ],
        ]),
    ]);

    $distance = app(GoogleRoutesService::class)->computeDistanceBetweenAreaTowns($from, $to);

    expect($distance)->toBe(8.4);
    Http::assertSentCount(1);
});

test('sfc distance service stores google routes distance on chart', function () {
    $from = CityPinCode::query()->create([
        'pin_code' => 400001,
        'area_town' => 'Fort',
        'latitude' => 18.9388,
        'longitude' => 72.8354,
    ]);

    $to = CityPinCode::query()->create([
        'pin_code' => 400050,
        'area_town' => 'Bandra',
        'latitude' => 19.0596,
        'longitude' => 72.8295,
    ]);

    Http::fake([
        'routes.googleapis.com/*' => Http::response([
            'routes' => [
                ['distanceMeters' => 15600],
            ],
        ]),
    ]);

    $chart = StandardFareChart::query()->create([
        'from_area_town_id' => $from->id,
        'to_area_town_id' => $to->id,
        'distance_km' => 0,
        'distance_source' => 'google_routes',
        'fare_amount' => 0,
        'is_active' => true,
    ]);

    expect((float) $chart->fresh()->distance_km)->toBe(15.6);
    expect($chart->fresh()->distance_source)->toBe('google_routes');
});

test('sfc distance service does not overwrite manual distances unless forced', function () {
    $from = CityPinCode::query()->create([
        'pin_code' => 500001,
        'area_town' => 'Abids',
        'latitude' => 17.3850,
        'longitude' => 78.4867,
    ]);

    $to = CityPinCode::query()->create([
        'pin_code' => 500032,
        'area_town' => 'Gachibowli',
        'latitude' => 17.4401,
        'longitude' => 78.3489,
    ]);

    Http::fake();

    $chart = StandardFareChart::query()->create([
        'from_area_town_id' => $from->id,
        'to_area_town_id' => $to->id,
        'distance_km' => 25,
        'distance_source' => 'manual',
        'fare_amount' => 500,
        'is_active' => true,
    ]);

    $updated = app(SfcDistanceService::class)->populateChartDistance($chart);

    expect($updated)->toBeFalse();
    expect((float) $chart->fresh()->distance_km)->toBe(25.0);
    Http::assertNothingSent();
});
