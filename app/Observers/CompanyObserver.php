<?php

namespace App\Observers;

use App\Models\Company;
use App\Models\CityPinCode;

class CompanyObserver
{
    /**
     * Handle the Company "created" event.
     */
    public function created(Company $company): void
    {
        // Save addresses from the form
        $addresses = request()->input('addresses', []);

        $companyId = $contact->company_id ?? request()->input('company_id');

        foreach ($addresses as $address) {
            // ✅ Inject `company_id` correctly into each address
            if (!isset($address['company_id'])) {
                $address['company_id'] = $companyId;
            }
            // Check for auto-fill logic using Pin Code or City
            if (!isset($address['state_id']) || !isset($address['country_id'])) {
                $pinCodeDetails = CityPinCode::where('pin_code', $address['pin_code'] ?? null)
                    ->orWhere('city_id', $address['city_id'] ?? null)
                    ->first();

                $address['state_id'] = $pinCodeDetails->state_id ?? null;
                $address['country_id'] = $pinCodeDetails->country_id ?? null;
            }

            $company->addresses()->create($address);
        }
    }

    /**
     * Handle the Company "updated" event.
     */
    public function updated(Company $company): void
    {
        // Sync addresses when a company is updated
        $addresses = request()->input('addresses', []);

        $company->addresses()->delete(); // Delete old addresses
        foreach ($addresses as $address) {
            // Check for auto-fill logic
            if (!isset($address['state_id']) || !isset($address['country_id'])) {
                $pinCodeDetails = CityPinCode::where('pin_code', $address['pin_code'] ?? null)
                    ->orWhere('city_id', $address['city_id'] ?? null)
                    ->first();

                $address['state_id'] = $pinCodeDetails->state_id ?? null;
                $address['country_id'] = $pinCodeDetails->country_id ?? null;
            }
            $address['address_type'] = 'Company';
            $company->addresses()->create($address);
        }
    }

    /**
     * Handle the Company "deleted" event.
     */
    public function deleted(Company $company): void
    {
        $company->addresses()->delete();
    }

    /**
     * Handle the Company "restored" event.
     */
    public function restored(Company $company): void
    {
        //
    }

    /**
     * Handle the Company "force deleted" event.
     */
    public function forceDeleted(Company $company): void
    {
        //
    }
}
