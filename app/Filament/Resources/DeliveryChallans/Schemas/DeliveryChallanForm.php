<?php

namespace App\Filament\Resources\DeliveryChallans\Schemas;

use App\Enums\DeliveryChallanStatus;
use App\Models\AccountMaster;
use App\Models\DeliveryChallan;
use App\Models\ItemMaster;
use App\Models\LocationMaster;
use App\Models\NumberSeries;
use App\Models\SalesDocumentItem;
use App\Models\SalesInvoice;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class DeliveryChallanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Delivery Details')
                    ->columns(4)
                    ->schema([
                        TextInput::make('document_number')
                            ->label('Challan Number')
                            ->default(fn (): string => NumberSeries::getNextNumber(DeliveryChallan::class))
                            ->disabled()
                            ->dehydrated(),

                        DatePicker::make('delivery_date')
                            ->label('Delivery Date')
                            ->default(now()->toDateString())
                            ->required(),

                        Select::make('status')
                            ->options(DeliveryChallanStatus::class)
                            ->default(DeliveryChallanStatus::Draft)
                            ->disabled()
                            ->dehydrated(),

                        Select::make('sales_invoice_id')
                            ->label('Sales Invoice')
                            ->options(fn (): array => SalesInvoice::query()
                                ->whereNotIn('status', ['canceled', 'rejected'])
                                ->whereHas('items', fn ($query) => $query
                                    ->whereNotNull('item_master_id')
                                    ->whereRaw('quantity > COALESCE(quantity_delivered, 0)'))
                                ->orderByDesc('date')
                                ->pluck('document_number', 'id')
                                ->all())
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function (?string $state, Set $set): void {
                                if (! $state) {
                                    return;
                                }

                                $invoice = SalesInvoice::query()
                                    ->with(['items.itemMaster', 'accountMaster'])
                                    ->find($state);

                                if ($invoice === null) {
                                    return;
                                }

                                $set('customer_id', $invoice->account_master_id);

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

                                $set('lines', $lines);
                            }),

                        Select::make('customer_id')
                            ->label('Customer')
                            ->options(fn (): array => AccountMaster::query()
                                ->orderBy('name')
                                ->pluck('name', 'id')
                                ->all())
                            ->searchable()
                            ->required(),

                        Select::make('location_master_id')
                            ->label('Dispatch Location')
                            ->options(fn (): array => self::locationOptions())
                            ->searchable()
                            ->required(),

                        Textarea::make('notes')
                            ->columnSpanFull(),
                    ])->columnSpanFull(),

                Section::make('Items to Deliver')
                    ->schema([
                        Repeater::make('lines')
                            ->relationship()
                            ->columns(5)
                            ->schema([
                                Select::make('sales_document_item_id')
                                    ->label('Invoice Line')
                                    ->options(function (Get $get): array {
                                        $salesInvoiceId = $get('../../sales_invoice_id');

                                        if (! $salesInvoiceId) {
                                            return [];
                                        }

                                        return SalesDocumentItem::query()
                                            ->where('document_type', SalesInvoice::class)
                                            ->where('document_id', $salesInvoiceId)
                                            ->whereNotNull('item_master_id')
                                            ->with('itemMaster')
                                            ->get()
                                            ->mapWithKeys(fn (SalesDocumentItem $item): array => [
                                                $item->id => ($item->itemMaster?->item_name ?? 'Item').' (Remaining: '.$item->remainingQuantity().')',
                                            ])
                                            ->all();
                                    })
                                    ->searchable()
                                    ->live()
                                    ->afterStateUpdated(function (?string $state, Set $set): void {
                                        if (! $state) {
                                            return;
                                        }

                                        $item = SalesDocumentItem::query()->with('itemMaster')->find($state);

                                        if ($item === null) {
                                            return;
                                        }

                                        $set('item_master_id', $item->item_master_id);
                                        $set('quantity_delivered', $item->remainingQuantity());
                                        $set('unit_cost', $item->unit_price ?? $item->itemMaster?->selling_price ?? 0);
                                    }),

                                Select::make('item_master_id')
                                    ->label('Item')
                                    ->options(fn (): array => ItemMaster::query()
                                        ->whereNull('parent_id')
                                        ->orderBy('item_name')
                                        ->pluck('item_name', 'id')
                                        ->all())
                                    ->searchable()
                                    ->required()
                                    ->columnSpan(2),

                                TextInput::make('quantity_delivered')
                                    ->label('Qty Delivered')
                                    ->numeric()
                                    ->minValue(0.001)
                                    ->step(0.001)
                                    ->required(),

                                TextInput::make('unit_cost')
                                    ->label('Unit Cost')
                                    ->numeric(),

                                TextInput::make('batch_number')
                                    ->label('Batch / Lot'),

                                Textarea::make('remarks')
                                    ->columnSpanFull()
                                    ->rows(1),
                            ])
                            ->columnSpanFull(),
                    ])->columnSpanFull(),
            ]);
    }

    protected static function locationOptions(): array
    {
        $locations = LocationMaster::query()
            ->where('is_active', true)
            ->whereNull('parent_id')
            ->with('subLocations')
            ->orderBy('name')
            ->get();

        $options = [];
        self::buildLocationOptions($locations, $options);

        return $options;
    }

    protected static function buildLocationOptions($locations, array &$options, string $prefix = ''): void
    {
        foreach ($locations as $location) {
            $options[$location->id] = $prefix.$location->name.' ['.$location->location_code.']';

            $activeSubLocations = $location->subLocations->where('is_active', true);

            if ($activeSubLocations->isNotEmpty()) {
                self::buildLocationOptions($activeSubLocations, $options, $prefix.'— ');
            }
        }
    }
}
