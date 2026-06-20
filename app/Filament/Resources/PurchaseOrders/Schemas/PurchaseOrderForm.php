<?php

namespace App\Filament\Resources\PurchaseOrders\Schemas;

use App\Enums\PurchaseOrderStatus;
use App\Models\AccountMaster;
use App\Models\ItemMaster;
use App\Models\LocationMaster;
use App\Models\NumberSeries;
use App\Models\PaymentTerm;
use App\Models\PurchaseOrder;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class PurchaseOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Order Details')
                    ->columns(4)
                    ->schema([
                        TextInput::make('document_number')
                            ->label('PO Number')
                            ->default(fn (): string => NumberSeries::getNextNumber(PurchaseOrder::class))
                            ->disabled()
                            ->dehydrated(),

                        DatePicker::make('order_date')
                            ->label('Order Date')
                            ->default(now()->toDateString())
                            ->required(),

                        DatePicker::make('expected_delivery_date')
                            ->label('Expected Delivery'),

                        Select::make('status')
                            ->options(PurchaseOrderStatus::class)
                            ->default(PurchaseOrderStatus::Draft)
                            ->disabled(fn (?PurchaseOrder $record): bool => $record !== null && ! $record->isEditable())
                            ->dehydrated(),

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

                        Select::make('payment_term_id')
                            ->label('Payment Terms')
                            ->options(fn (): array => PaymentTerm::query()->pluck('name', 'id')->all())
                            ->searchable(),

                        TextInput::make('currency')
                            ->default('INR')
                            ->maxLength(3)
                            ->required(),

                        TextInput::make('subtotal')
                            ->numeric()
                            ->prefix('₹')
                            ->disabled()
                            ->dehydrated(),

                        TextInput::make('tax_amount')
                            ->numeric()
                            ->prefix('₹')
                            ->disabled()
                            ->dehydrated(),

                        TextInput::make('total_amount')
                            ->numeric()
                            ->prefix('₹')
                            ->disabled()
                            ->dehydrated(),

                        Textarea::make('notes')
                            ->columnSpanFull(),
                    ])->columnSpanFull(),

                Section::make('Line Items')
                    ->schema([
                        Repeater::make('lines')
                            ->relationship()
                            ->columns(6)
                            ->schema([
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

                                        $set('unit_price', $item->purchase_price ?? 0);
                                        $set('tax_rate', $item->tax_rate ?? 0);
                                    }),

                                TextInput::make('quantity_ordered')
                                    ->label('Qty Ordered')
                                    ->numeric()
                                    ->minValue(0.001)
                                    ->step(0.001)
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, Get $get) => self::recalculateLine($set, $get)),

                                TextInput::make('quantity_received')
                                    ->label('Qty Received')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated(),

                                TextInput::make('unit_price')
                                    ->label('Unit Price')
                                    ->numeric()
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, Get $get) => self::recalculateLine($set, $get)),

                                TextInput::make('tax_rate')
                                    ->label('Tax %')
                                    ->numeric()
                                    ->default(0)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, Get $get) => self::recalculateLine($set, $get)),

                                TextInput::make('line_total')
                                    ->label('Line Total')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated(),

                                Textarea::make('description')
                                    ->columnSpanFull()
                                    ->rows(1),
                            ])
                            ->columnSpanFull(),
                    ])->columnSpanFull(),
            ]);
    }

    protected static function recalculateLine(Set $set, Get $get): void
    {
        $quantity = (float) ($get('quantity_ordered') ?? 0);
        $unitPrice = (float) ($get('unit_price') ?? 0);

        $set('line_total', round($quantity * $unitPrice, 2));
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
