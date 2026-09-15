<?php

use App\Helpers\SalesDocumentHelper;
use App\Models\Quote;
use App\Models\SalesOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('copies quote headers and lines onto a sales order', function () {
    $rep = reportingUser('QuoteRep');
    $account = reportingDoctor($rep, 'Quote Doctor');
    $contact = reportingContact('Quote', 'Buyer');
    $item = reportingSampleItem('QIT', 'Quoted Item');

    $this->actingAs($rep);

    $quote = Quote::query()->create([
        'document_number' => 'QT-'.fake()->unique()->numerify('####'),
        'contact_detail_id' => $contact->id,
        'account_master_id' => $account->id,
        'date' => today(),
        'status' => 'draft',
        'sales_person_id' => $rep->id,
        'subtotal' => 200,
        'total' => 200,
        'created_by' => $rep->id,
        'updated_by' => $rep->id,
    ]);

    $quote->items()->create([
        'item_master_id' => $item->id,
        'quantity' => 2,
        'price' => 100,
        'unit_price' => 100,
        'amount' => 200,
        'final_taxable_amount' => 200,
        'created_by' => $rep->id,
        'updated_by' => $rep->id,
    ]);

    $order = SalesDocumentHelper::createFrom($quote->fresh(['items']), SalesOrder::class);

    expect($order)->toBeInstanceOf(SalesOrder::class)
        ->and($order->account_master_id)->toBe($account->id)
        ->and($order->contact_detail_id)->toBe($contact->id)
        ->and($order->status)->toBe('draft')
        ->and($order->items)->toHaveCount(1)
        ->and((float) $order->items->first()->quantity)->toBe(2.0)
        ->and($quote->fresh()->salesOrders()->whereKey($order->id)->exists())->toBeTrue();
});
