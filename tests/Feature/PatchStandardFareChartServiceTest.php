<?php

use App\Models\AccountMaster;
use App\Models\Address;
use App\Models\CityClass;
use App\Models\CityPinCode;
use App\Models\Patch;
use App\Models\StandardFareChart;
use App\Models\Territory;
use App\Models\TypeMaster;
use App\Models\User;
use App\Services\Travel\PatchStandardFareChartService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createAreaTown(string $name, int $pinCode): CityPinCode
{
    $cityClass = CityClass::query()->firstOrCreate(['code' => 'a'], ['name' => 'A']);

    return CityPinCode::query()->create([
        'pin_code' => $pinCode,
        'area_town' => $name,
        'city_class_id' => $cityClass->id,
        'is_hill_station' => false,
    ]);
}

function createCustomerWithAreaTown(CityPinCode $areaTown): AccountMaster
{
    $customerType = TypeMaster::query()->firstOrCreate(
        ['name' => 'Customer', 'typeable_type' => AccountMaster::class],
        ['parent_id' => null],
    );

    $account = AccountMaster::query()->create([
        'name' => fake()->company(),
        'owner_id' => User::factory()->create()->id,
        'type_master_id' => $customerType->id,
    ]);

    Address::query()->create([
        'addressable_id' => $account->id,
        'addressable_type' => AccountMaster::class,
        'street' => fake()->streetAddress(),
        'area_town_id' => $areaTown->id,
        'pin_code' => $areaTown->pin_code,
        'sort' => 1,
        'is_primary' => true,
    ]);

    return $account;
}

test('it creates placeholder sfc routes when customers are assigned to a patch', function () {
    $territory = Territory::query()->create(['name' => 'West']);

    $hq = createAreaTown('HQ Town', 380001);
    $townA = createAreaTown('Town A', 380002);
    $townB = createAreaTown('Town B', 380003);

    $patch = Patch::query()->create([
        'name' => 'Patch 1',
        'code' => 'P1',
        'territory_id' => $territory->id,
        'city_pin_code_id' => $hq->id,
    ]);

    $customerA = createCustomerWithAreaTown($townA);
    $customerB = createCustomerWithAreaTown($townB);

    $patch->companies()->attach($customerA->id, ['sequence_no' => 1]);
    $patch->companies()->attach($customerB->id, ['sequence_no' => 2]);

    [$hqToAFrom, $hqToATo] = StandardFareChart::normalizeCityPair($hq->id, $townA->id);
    [$hqToBFrom, $hqToBTo] = StandardFareChart::normalizeCityPair($hq->id, $townB->id);
    [$aToBFrom, $aToBTo] = StandardFareChart::normalizeCityPair($townA->id, $townB->id);

    expect(StandardFareChart::query()->count())->toBe(3);

    expect(StandardFareChart::query()->where([
        'from_area_town_id' => $hqToAFrom,
        'to_area_town_id' => $hqToATo,
        'territory_id' => $territory->id,
        'distance_km' => 0,
        'fare_amount' => 0,
        'patch_id' => $patch->id,
    ])->exists())->toBeTrue();

    expect(StandardFareChart::query()->where([
        'from_area_town_id' => $hqToBFrom,
        'to_area_town_id' => $hqToBTo,
        'territory_id' => $territory->id,
    ])->exists())->toBeTrue();

    expect(StandardFareChart::query()->where([
        'from_area_town_id' => $aToBFrom,
        'to_area_town_id' => $aToBTo,
        'territory_id' => $territory->id,
    ])->exists())->toBeTrue();
});

test('it does not duplicate existing sfc routes for a patch', function () {
    $territory = Territory::query()->create(['name' => 'North']);

    $hq = createAreaTown('HQ North', 110001);
    $town = createAreaTown('Customer Town', 110002);

    $patch = Patch::query()->create([
        'name' => 'Patch 2',
        'code' => 'P2',
        'territory_id' => $territory->id,
        'city_pin_code_id' => $hq->id,
    ]);

    [$fromId, $toId] = StandardFareChart::normalizeCityPair($hq->id, $town->id);

    StandardFareChart::query()->create([
        'from_area_town_id' => $fromId,
        'to_area_town_id' => $toId,
        'territory_id' => $territory->id,
        'distance_km' => 42,
        'fare_amount' => 500,
        'is_active' => true,
    ]);

    $customer = createCustomerWithAreaTown($town);
    $patch->companies()->attach($customer->id, ['sequence_no' => 1]);

    expect(StandardFareChart::query()->count())->toBe(1);
    expect((float) StandardFareChart::query()->first()->distance_km)->toBe(42.0);
});

test('service skips placeholder routes that already exist', function () {
    $territory = Territory::query()->create(['name' => 'East']);

    $hq = createAreaTown('HQ East', 400001);
    $town = createAreaTown('East Town', 400002);

    $patch = Patch::query()->create([
        'name' => 'Patch 3',
        'code' => 'P3',
        'territory_id' => $territory->id,
        'city_pin_code_id' => $hq->id,
    ]);

    $customer = createCustomerWithAreaTown($town);
    $patch->companies()->attach($customer->id, ['sequence_no' => 1]);

    $created = app(PatchStandardFareChartService::class)->ensureForPatch($patch->fresh());

    expect($created)->toBe(0);
    expect(StandardFareChart::query()->count())->toBe(1);
});
