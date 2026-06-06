<?php

use App\Models\CityClass;
use App\Models\CityPinCode;
use App\Models\ExpenseType;
use App\Models\SalesDcr;
use App\Models\SalesDcrExpense;
use App\Models\Territory;
use App\Models\TravelSegment;
use App\Models\User;
use App\Services\Expense\ExpenseCalculationService;
use Database\Seeders\ExpensePolicySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\seed;

uses(RefreshDatabase::class);

test('it auto-calculates DA + travel expense using seeded rules', function () {
    seed(ExpensePolicySeeder::class);

    $hqTerritory = Territory::query()->create(['name' => 'HQ']);
    $fieldTerritory = Territory::query()->create(['name' => 'Field']);

    $user = User::factory()->create();

    $dcr = SalesDcr::query()->create([
        'dcr_date' => now()->toDateString(),
        'user_id' => $user->id,
        'territory_id' => $fieldTerritory->id,
    ]);

    $cityClassA = CityClass::query()->firstOrCreate(['code' => 'a'], ['name' => 'A']);
    $areaTown = CityPinCode::query()->create([
        'pin_code' => 110001,
        'area_town' => 'Test Town',
        'city_class_id' => $cityClassA->id,
        'is_hill_station' => true,
    ]);

    TravelSegment::query()->create([
        'sales_dcr_id' => $dcr->id,
        'distance_km' => 250,
        'distance_source' => 'manual',
        'to_area_town_id' => $areaTown->id,
    ]);

    app(ExpenseCalculationService::class)->autoCalculateDcrExpenses($dcr->refresh());

    $daTypeId = ExpenseType::query()->where('code', 'DAILY_ALLOWANCE')->value('id');
    $travelTypeId = ExpenseType::query()->where('code', 'TRAVEL')->value('id');

    expect($daTypeId)->not->toBeNull();
    expect($travelTypeId)->not->toBeNull();

    $da = SalesDcrExpense::query()
        ->where('sales_dcr_id', $dcr->id)
        ->where('expense_type_id', $daTypeId)
        ->firstOrFail();

    $travel = SalesDcrExpense::query()
        ->where('sales_dcr_id', $dcr->id)
        ->where('expense_type_id', $travelTypeId)
        ->firstOrFail();

    // EX_STATION DA (330) with hill multiplier (1.5) => 495
    expect((float) $da->amount)->toBe(495.0);

    // Travel slab for 250km => rate 2.5 => 625
    expect((float) $travel->amount)->toBe(625.0);

    $dcr->refresh();
    expect((float) $dcr->total_expense)->toBe(1120.0);
});
