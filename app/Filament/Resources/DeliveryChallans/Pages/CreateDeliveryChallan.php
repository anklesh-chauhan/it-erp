<?php

namespace App\Filament\Resources\DeliveryChallans\Pages;

use App\Filament\Resources\DeliveryChallans\DeliveryChallanResource;
use App\Models\SalesDocumentItem;
use App\Models\SalesInvoice;
use Filament\Resources\Pages\CreateRecord;

class CreateDeliveryChallan extends CreateRecord
{
    protected static string $resource = DeliveryChallanResource::class;

    public function mount(): void
    {
        parent::mount();

        $salesInvoiceId = request()->query('sales_invoice_id');

        if (! $salesInvoiceId) {
            return;
        }

        $invoice = SalesInvoice::query()
            ->with(['items.itemMaster'])
            ->find($salesInvoiceId);

        if ($invoice === null) {
            return;
        }

        $lines = $invoice->items
            ->filter(fn (SalesDocumentItem $item): bool => $item->item_master_id !== null && $item->remainingQuantity() > 0)
            ->map(fn (SalesDocumentItem $item): array => [
                'sales_document_item_id' => $item->id,
                'item_master_id' => $item->item_master_id,
                'quantity_delivered' => $item->remainingQuantity(),
                'unit_cost' => $item->unit_price ?? $item->itemMaster?->selling_price ?? 0,
            ])
            ->values()
            ->all();

        $this->form->fill([
            'sales_invoice_id' => $invoice->id,
            'customer_id' => $invoice->account_master_id,
            'delivery_date' => now()->toDateString(),
            'lines' => $lines,
        ]);
    }
}
