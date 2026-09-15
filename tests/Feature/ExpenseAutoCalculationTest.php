<?php

use App\Models\City;
use App\Models\CityClass;
use App\Models\CityPinCode;
use App\Models\Country;
use App\Models\ExpenseType;
use App\Models\SalesDcr;
use App\Models\SalesDcrExpense;
use App\Models\State;
use App\Models\Territory;
use App\Models\TravelSegment;
use App\Models\Visit;
use App\Services\Expense\ExpenseCalculationService;
use Database\Seeders\ExpensePolicySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\seed;

uses(RefreshDatabase::class);

test('it auto-calculates DA + travel expense using seeded rules', function () {
    seed(ExpensePolicySeeder::class);

    $user = reportingUser('ExpenseRep');
    $this->actingAs($user);

    $hqTerritory = Territory::query()->create(['name' => 'HQ']);
    $fieldTerritory = Territory::query()->create(['name' => 'Field']);
    $patch = reportingPatch($fieldTerritory, 'Expense Patch');

    $dcr = SalesDcr::query()->create([
        'dcr_date' => now()->toDateString(),
        'user_id' => $user->id,
        'territory_id' => $fieldTerritory->id,
        'created_by' => $user->id,
        'updated_by' => $user->id,
    ]);

    $cityClassA = CityClass::query()->firstOrCreate(['code' => 'a'], ['name' => 'A']);
    $country = Country::query()->create(['name' => 'Testland '.fake()->unique()->numerify('###')]);
    $state = State::query()->create([
        'name' => 'Hill State '.fake()->unique()->numerify('###'),
        'country_id' => $country->id,
    ]);
    $city = City::query()->create([
        'name' => 'Hill City '.fake()->unique()->numerify('###'),
        'state_id' => $state->id,
        'country_id' => $country->id,
        'city_class_id' => $cityClassA->id,
        'is_hill_station' => true,
    ]);
    $areaTown = CityPinCode::query()->create([
        'pin_code' => 110001,
        'area_town' => 'Test Town',
        'city_id' => $city->id,
    ]);

    TravelSegment::query()->create([
        'sales_dcr_id' => $dcr->id,
        'distance_km' => 250,
        'distance_source' => 'manual',
        'to_area_town_id' => $areaTown->id,
        'created_by' => $user->id,
        'updated_by' => $user->id,
    ]);

    Visit::withoutEvents(fn (): Visit => Visit::query()->create([
        'document_number' => 'VIS-EXP-'.fake()->unique()->numerify('####'),
        'employee_id' => $user->id,
        'territory_id' => $fieldTerritory->id,
        'patch_id' => $patch->id,
        'sales_dcr_id' => $dcr->id,
        'visit_date' => today(),
        'visit_type' => 'unplanned',
        'visit_status' => 'draft',
        'approval_status' => 'draft',
        'created_by' => $user->id,
        'updated_by' => $user->id,
    ]));

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

    expect((float) $da->amount)->toBe(495.0);
    expect((float) $travel->amount)->toBe(625.0);

    $dcr->refresh();
    expect((float) $dcr->total_expense)->toBe(1120.0);
});
