<?php

use App\Models\Address;
use App\Models\City;
use App\Models\CityPinCode;
use App\Models\Country;
use App\Models\Organization;
use App\Models\State;
use App\Traits\SalesDocumentResourceTrait;
use Illuminate\Database\Eloquent\Builder;

uses(Tests\TestCase::class);
uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

it('uses the organization address state for GST calculation even when model scopes are applied', function () {
    Organization::addGlobalScope('tax_visibility_guard', fn (Builder $query) => $query->whereRaw('1 = 0'));
    Address::addGlobalScope('tax_visibility_guard', fn (Builder $query) => $query->whereRaw('1 = 0'));

    $country = Country::create(['name' => 'India']);
    $state = State::create([
        'name' => 'Gujarat',
        'country_id' => $country->id,
        'gst_code' => 'GJ',
    ]);
    $city = City::create([
        'name' => 'Ahmedabad',
        'state_id' => $state->id,
        'country_id' => $country->id,
        'city_class_id' => null,
        'is_hill_station' => false,
    ]);
    $pinCode = CityPinCode::create([
        'pin_code' => '380001',
        'area_town' => 'Navrangpura',
        'city_id' => $city->id,
        'state_id' => $state->id,
        'country_id' => $country->id,
        'latitude' => 23.03,
        'longitude' => 72.55,
    ]);

    $organization = Organization::withoutGlobalScope('tax_visibility_guard')->create([
        'name' => 'Test Organization',
        'display_name' => 'Test Organization',
        'email' => 'org@example.com',
        'status' => 'active',
    ]);

    $organizationAddress = Address::create([
        'company_id' => null,
        'contact_detail_id' => null,
        'street' => 'Test Street',
        'area_town_id' => $pinCode->id,
        'pin_code' => $pinCode->pin_code,
        'city_id' => $city->id,
        'state_id' => $state->id,
        'country_id' => $country->id,
        'sort' => 1,
        'is_primary' => true,
        'addressable_id' => $organization->id,
        'addressable_type' => Organization::class,
    ]);

    $billingAddress = Address::create([
        'company_id' => null,
        'contact_detail_id' => null,
        'street' => 'Billing Street',
        'area_town_id' => $pinCode->id,
        'pin_code' => $pinCode->pin_code,
        'city_id' => $city->id,
        'state_id' => $state->id,
        'country_id' => $country->id,
        'sort' => 1,
        'is_primary' => false,
        'addressable_id' => null,
        'addressable_type' => null,
    ]);

    $subject = new class
    {
        use SalesDocumentResourceTrait;
    };

    $method = new ReflectionMethod($subject, 'shouldShowCgstSgst');
    $method->setAccessible(true);

    $result = $method->invoke(null, fn (string $field) => $field === 'billing_address_id' ? $billingAddress->id : null);

    expect($result)->toBeTrue();
})->afterEach(function () {
    Organization::clearGlobalScopes();
    Address::clearGlobalScopes();
});
