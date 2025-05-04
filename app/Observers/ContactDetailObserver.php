<?php

namespace App\Observers;

use App\Models\ContactDetail;
use App\Models\CityPinCode;

class ContactDetailObserver
{
    /**
     * Handle the ContactDetail "created" event.
     */
    public function created(ContactDetail $contact): void
    {
        $addresses = request()->input('addresses', []);

        // dd($addresses);

        // Get the correct `company_id` (newly created or selected)
        $companyId = $contact->company_id ?? request()->input('company_id');

        foreach ($addresses as $address) {

            // ✅ Inject `company_id` correctly into each address
            if (!isset($address['company_id'])) {
                $address['company_id'] = $companyId;
            }

            dd($address);
            // Auto-set address_type
            if (!isset($address['address_type']) || empty($address['address_type'])) {
                $address['address_type'] = $address['company_id'] ? 'Company' : 'Other';
            }

            // Auto-fill state & country using Pin Code or City
            if (!isset($address['state_id']) || !isset($address['country_id'])) {
                $pinCodeDetails = CityPinCode::where('pin_code', $address['pin_code'] ?? null)
                    ->orWhere('city_id', $address['city_id'] ?? null)
                    ->first();

                $address['state_id'] = $pinCodeDetails->state_id ?? null;
                $address['country_id'] = $pinCodeDetails->country_id ?? null;
            }

            $contact->addresses()->create($address);
        }
    }

    /**
     * Handle the ContactDetail "updated" event.
     */
    public function updated(ContactDetail $contact): void
    {
        $addresses = request()->input('addresses', []);

        // Get the newly created company ID (if created via Contact Form)
        $companyId = $contact->company_id ?? request()->input('company_id');


        $contact->addresses()->delete(); // Delete old addresses

        foreach ($addresses as $address) {
            // Assign the correct `company_id`
            if ($companyId) {
                $address['company_id'] = $companyId;
            }

            // Auto-set address_type
            if (!isset($address['address_type']) || empty($address['address_type'])) {
                $address['address_type'] = $address['company_id'] ? 'Company' : 'Other';
            }

            // Auto-fill state & country
            if (!isset($address['state_id']) || !isset($address['country_id'])) {
                $pinCodeDetails = CityPinCode::where('pin_code', $address['pin_code'] ?? null)
                    ->orWhere('city_id', $address['city_id'] ?? null)
                    ->first();

                $address['state_id'] = $pinCodeDetails->state_id ?? null;
                $address['country_id'] = $pinCodeDetails->country_id ?? null;
            }

            $contact->addresses()->create($address);
        }
    }

    /**
     * Handle the ContactDetail "deleted" event.
     */
    public function deleted(ContactDetail $contact): void
    {
        $contact->addresses()->delete();
    }
}
