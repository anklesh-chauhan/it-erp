<?php

namespace App\Filament\Resources\CustomerPrices\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Decimal;
use App\Models\CustomerPrice;

class CustomerPriceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('customer_id')
                    ->label('Customer')
                    ->relationship('customer', 'name')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn ($state, callable $set) => $set('item_variant_id', null)),

                Select::make('item_master_id')
                    ->label('Item')
                    ->relationship('itemMaster', 'item_name')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn ($state, callable $set) => $set('item_variant_id', null))
                    ->rules([
                        function ($get, $component) {
                            return function ($attribute, $value, $fail) use ($get, $component) {
                                $customerId = $get('customer_id');
                                $itemMasterId = $value; // item_master_id is the current field value
                                $itemVariantId = $get('item_variant_id'); // Can be null
                                $recordId = $component->getLivewire()->record?->id;

                                if (!$customerId || !$itemMasterId) {
                                    return; // Skip if required fields are missing
                                }

                                $query = CustomerPrice::where('customer_id', $customerId)
                                    ->where('item_master_id', $itemMasterId);

                                if ($itemVariantId === null) {
                                    $query->whereNull('item_variant_id');
                                } else {
                                    $query->where('item_variant_id', $itemVariantId);
                                }

                                if ($recordId) {
                                    $query->where('id', '!=', $recordId);
                                }

                                if ($query->exists()) {
                                    $errorMessage = $itemVariantId === null
                                        ? 'A general price for this customer and item already exists.'
                                        : 'A price for this customer, item, and variant combination already exists.';
                                    $fail($errorMessage);
                                }
                            };
                        },
                    ]),

                Select::make('item_variant_id')
                    ->label('Variant')
                    ->relationship('itemVariant', 'variant_name', function ($query, $get) {
                        $itemMasterId = $get('item_master_id');
                        if ($itemMasterId) {
                            $query->where('item_master_id', $itemMasterId);
                        }
                    })
                    ->nullable()
                    ->reactive(),

                TextInput::make('price')
                    ->numeric()
                    ->required()
                    ->inputMode('decimal'),

                TextInput::make('discount')
                    ->numeric()
                    ->suffix('%')
                    ->default(0)
                    ->inputMode('decimal')
                    ->maxValue(100),
            ]);
    }
}