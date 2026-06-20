<?php

namespace App\Filament\Resources\GoodsReceiptNotes\Schemas;

use App\Enums\GoodsReceiptNoteStatus;
use App\Enums\PurchaseOrderStatus;
use App\Models\AccountMaster;
use App\Models\GoodsReceiptNote;
use App\Models\ItemMaster;
use App\Models\LocationMaster;
use App\Models\NumberSeries;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderLine;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class GoodsReceiptNoteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Receipt Details')
                    ->columns(4)
                    ->schema([
                        TextInput::make('document_number')
                            ->label('GRN Number')
                            ->default(fn (): string => NumberSeries::getNextNumber(GoodsReceiptNote::class))
                            ->disabled()
                            ->dehydrated(),

                        DatePicker::make('receipt_date')
                            ->label('Receipt Date')
                            ->default(now()->toDateString())
                            ->required(),

                        Select::make('status')
                            ->options(GoodsReceiptNoteStatus::class)
                            ->default(GoodsReceiptNoteStatus::Draft)
                            ->disabled()
                            ->dehydrated(),

                        Select::make('purchase_order_id')
                            ->label('Purchase Order')
                            ->options(fn (): array => PurchaseOrder::query()
                                ->whereIn('status', [
                                    PurchaseOrderStatus::Approved,
                                    PurchaseOrderStatus::PartiallyReceived,
                                ])
                                ->orderByDesc('order_date')
                                ->pluck('document_number', 'id')
                                ->all())
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function (?string $state, Set $set): void {
                                if (! $state) {
                                    return;
                                }

                                $purchaseOrder = PurchaseOrder::query()
                                    ->with('lines.item')
                                    ->find($state);

                                if ($purchaseOrder === null) {
                                    return;
                                }

                                $set('supplier_id', $purchaseOrder->supplier_id);
                                $set('location_master_id', $purchaseOrder->location_master_id);

                                $lines = $purchaseOrder->lines
                                    ->filter(fn (PurchaseOrderLine $line): bool => $line->remainingQuantity() > 0)
                                    ->map(fn (PurchaseOrderLine $line): array => [
                                        'purchase_order_line_id' => $line->id,
                                        'item_master_id' => $line->item_master_id,
                                        'quantity_received' => $line->remainingQuantity(),
                                        'unit_cost' => $line->unit_price,
                                    ])
                                    ->values()
                                    ->all();

                                $set('lines', $lines);
                            }),

                        Select::make('supplier_id')
                            ->label('Supplier')
                            ->options(fn (): array => AccountMaster::query()
                                ->whereHas('typeMaster', fn ($query) => $query->where('name', 'Vendor')
                                    ->orWhereHas('parent', fn ($parentQuery) => $parentQuery->where('name', 'Vendor')))
                                ->orderBy('name')
                                ->pluck('name', 'id')
                                ->all())
                            ->searchable()
                            ->required(),

                        Select::make('location_master_id')
                            ->label('Receiving Location')
                            ->options(fn (): array => self::locationOptions())
                            ->searchable()
                            ->required(),

                        Textarea::make('notes')
                            ->columnSpanFull(),
                    ]),

                Section::make('Received Items')
                    ->schema([
                        Repeater::make('lines')
                            ->relationship()
                            ->columns(5)
                            ->schema([
                                Select::make('purchase_order_line_id')
                                    ->label('PO Line')
                                    ->options(function (Get $get): array {
                                        $purchaseOrderId = $get('../../purchase_order_id');

                                        if (! $purchaseOrderId) {
                                            return [];
                                        }

                                        return PurchaseOrderLine::query()
                                            ->where('purchase_order_id', $purchaseOrderId)
                                            ->with('item')
                                            ->get()
                                            ->mapWithKeys(fn (PurchaseOrderLine $line): array => [
                                                $line->id => ($line->item?->item_name ?? 'Item').' (Remaining: '.$line->remainingQuantity().')',
                                            ])
                                            ->all();
                                    })
                                    ->searchable()
                                    ->live()
                                    ->afterStateUpdated(function (?string $state, Set $set): void {
                                        if (! $state) {
                                            return;
                                        }

                                        $line = PurchaseOrderLine::query()->find($state);

                                        if ($line === null) {
                                            return;
                                        }

                                        $set('item_master_id', $line->item_master_id);
                                        $set('quantity_received', $line->remainingQuantity());
                                        $set('unit_cost', $line->unit_price);
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
                                    ->columnSpan(2)
                                    ->live()
                                    ->afterStateUpdated(function (?string $state, Set $set): void {
                                        if (! $state) {
                                            return;
                                        }

                                        $item = ItemMaster::query()->find($state);

                                        if ($item === null) {
                                            return;
                                        }

                                        $set('unit_cost', $item->purchase_price ?? 0);
                                    }),

                                TextInput::make('quantity_received')
                                    ->label('Qty Received')
                                    ->numeric()
                                    ->minValue(0.001)
                                    ->step(0.001)
                                    ->required(),

                                TextInput::make('unit_cost')
                                    ->label('Unit Cost')
                                    ->numeric()
                                    ->required(),

                                TextInput::make('batch_number')
                                    ->label('Batch / Lot'),

                                Textarea::make('remarks')
                                    ->columnSpanFull()
                                    ->rows(1),
                            ])
                            ->columnSpanFull(),
                    ]),
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
