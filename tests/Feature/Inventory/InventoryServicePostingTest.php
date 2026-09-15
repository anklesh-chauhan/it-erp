<?php

use App\Enums\InventoryAdjustmentType;
use App\Enums\InventoryDocumentStatus;
use App\Models\InventoryAdjustment;
use App\Models\InventoryMovement;
use App\Models\InventoryStock;
use App\Models\InventoryTransfer;
use App\Services\Inventory\InventoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('posts a receipt adjustment into stock and is idempotent', function () {
    $user = reportingUser('StockRep');
    $item = reportingSampleItem('ADJ', 'Adjustment Sample');
    $location = reportingLocation('Warehouse');

    $this->actingAs($user);

    $adjustment = InventoryAdjustment::query()->create([
        'item_master_id' => $item->id,
        'location_master_id' => $location->id,
        'adjustment_type' => InventoryAdjustmentType::Receipt,
        'quantity' => 10,
        'unit_cost' => 5,
        'reason' => 'Opening receipt',
        'status' => InventoryDocumentStatus::Draft,
    ]);

    $service = app(InventoryService::class);
    $service->postAdjustment($adjustment, $user->id);
    $service->postAdjustment($adjustment->fresh(), $user->id);

    $stock = InventoryStock::query()
        ->where('item_master_id', $item->id)
        ->where('location_master_id', $location->id)
        ->firstOrFail();

    expect((float) $stock->quantity_on_hand)->toBe(10.0)
        ->and(InventoryMovement::query()->where('movement_type', 'adjustment_receipt')->count())->toBe(1)
        ->and($adjustment->fresh()->isPosted())->toBeTrue();
});

it('rejects an issue when stock is insufficient', function () {
    $user = reportingUser('IssueRep');
    $item = reportingSampleItem('ISS', 'Issue Sample');
    $location = reportingLocation('Field Bag');

    $this->actingAs($user);

    $adjustment = InventoryAdjustment::query()->create([
        'item_master_id' => $item->id,
        'location_master_id' => $location->id,
        'adjustment_type' => InventoryAdjustmentType::Issue,
        'quantity' => 3,
        'reason' => 'Field issue',
        'status' => InventoryDocumentStatus::Draft,
    ]);

    expect(fn () => app(InventoryService::class)->postAdjustment($adjustment, $user->id))
        ->toThrow(RuntimeException::class, 'Insufficient stock for this inventory operation.');
});

it('moves stock between locations on transfer', function () {
    $user = reportingUser('TransferRep');
    $item = reportingSampleItem('TRN', 'Transfer Sample');
    $from = reportingLocation('From Loc');
    $to = reportingLocation('To Loc');

    $this->actingAs($user);

    app(InventoryService::class)->postAdjustment(InventoryAdjustment::query()->create([
        'item_master_id' => $item->id,
        'location_master_id' => $from->id,
        'adjustment_type' => InventoryAdjustmentType::Increase,
        'quantity' => 8,
        'unit_cost' => 2,
        'reason' => 'Seed stock',
        'status' => InventoryDocumentStatus::Draft,
    ]), $user->id);

    $transfer = InventoryTransfer::query()->create([
        'item_master_id' => $item->id,
        'from_location_master_id' => $from->id,
        'to_location_master_id' => $to->id,
        'quantity' => 3,
        'unit_cost' => 2,
        'status' => InventoryDocumentStatus::Draft,
    ]);

    app(InventoryService::class)->postTransfer($transfer, $user->id);

    expect((float) app(InventoryService::class)->stockFor($item->id, $from->id)->quantity_on_hand)->toBe(5.0)
        ->and((float) app(InventoryService::class)->stockFor($item->id, $to->id)->quantity_on_hand)->toBe(3.0);
});
