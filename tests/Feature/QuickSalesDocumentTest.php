<?php

use App\Helpers\SalesDocumentQuickCreate;
use App\Models\AccountMaster;
use App\Models\Address;
use App\Models\ContactDetail;
use App\Models\Quote;
use App\Models\SalesOrder;
use App\Models\User;

it('builds a quote draft with the visit customer details prefilled', function () {
    $account = new AccountMaster;
    $account->forceFill(['id' => 101, 'name' => 'Acme Industries']);

    $contact = new ContactDetail;
    $contact->forceFill(['id' => 202, 'full_name' => 'Jane Doe']);

    $address = new Address;
    $address->forceFill(['id' => 303, 'address_type' => 'Billing']);

    $salesPerson = new User;
    $salesPerson->forceFill(['id' => 404, 'name' => 'Field Sales']);

    $draft = SalesDocumentQuickCreate::buildDraftData(
        $account,
        $contact,
        $address,
        $salesPerson,
        Quote::class,
    );

    expect($draft)
        ->toHaveKeys(['account_master_id', 'contact_detail_id', 'billing_address_id', 'shipping_address_id', 'sales_person_id', 'currency', 'status', 'date'])
        ->and($draft['account_master_id'])->toBe(101)
        ->and($draft['contact_detail_id'])->toBe(202)
        ->and($draft['billing_address_id'])->toBe(303)
        ->and($draft['shipping_address_id'])->toBe(303)
        ->and($draft['sales_person_id'])->toBe(404)
        ->and($draft['currency'])->toBe('INR')
        ->and($draft['status'])->toBe('draft');
});

it('builds a sales order draft with the visit customer details prefilled', function () {
    $account = new AccountMaster;
    $account->forceFill(['id' => 505, 'name' => 'Northwind Pvt Ltd']);

    $contact = new ContactDetail;
    $contact->forceFill(['id' => 606, 'full_name' => 'Rahul Kumar']);

    $address = new Address;
    $address->forceFill(['id' => 707, 'address_type' => 'Billing']);

    $salesPerson = new User;
    $salesPerson->forceFill(['id' => 808, 'name' => 'Ravi']);

    $draft = SalesDocumentQuickCreate::buildDraftData(
        $account,
        $contact,
        $address,
        $salesPerson,
        SalesOrder::class,
    );

    expect($draft)
        ->toHaveKeys(['account_master_id', 'contact_detail_id', 'billing_address_id', 'shipping_address_id', 'sales_person_id', 'currency', 'status', 'date'])
        ->and($draft['account_master_id'])->toBe(505)
        ->and($draft['contact_detail_id'])->toBe(606)
        ->and($draft['billing_address_id'])->toBe(707)
        ->and($draft['shipping_address_id'])->toBe(707)
        ->and($draft['sales_person_id'])->toBe(808)
        ->and($draft['currency'])->toBe('INR')
        ->and($draft['status'])->toBe('draft');
});
