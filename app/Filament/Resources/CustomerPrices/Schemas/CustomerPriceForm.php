<?php

namespace App\Filament\Resources\CustomerPrices\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use App\Models\CustomerPrice;

class CustomerPriceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Select Customer
                Select::make('customer_id')
                    ->label('Customer')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->reactive(),

                // Select Item (can be main item or variant)
                Select::make('item_master_id')
    ->label('Item / Variant')
    ->relationship('item', 'item_name', function ($query) {
        $query->orderBy('parent_id')->orderBy('item_name');
    })
    ->getOptionLabelFromRecordUsing(function ($record) {
        // If the item has a parent, show as "Parent – Child"
        return $record->parent
            ? "{$record->parent->item_name} – {$record->variant_name}"
            : $record->item_name;
    })
    ->searchable()
    ->preload()
    ->required()
    ->rules([
        function ($get, $component) {
            return function ($attribute, $value, $fail) use ($get, $component) {
                $customerId = $get('customer_id');
                $itemMasterId = $value;
                $recordId = $component->getLivewire()->record?->id;

                if (!$customerId || !$itemMasterId) {
                    return;
                }

                $exists = \App\Models\CustomerPrice::where('customer_id', $customerId)
                    ->where('item_master_id', $itemMasterId)
                    ->when($recordId, fn($q) => $q->where('id', '!=', $recordId))
                    ->exists();

                if ($exists) {
                    $fail('A price for this customer and item/variant already exists.');
                }
            };
        },
    ]),

                // Price
                TextInput::make('price')
                    ->label('Price')
                    ->numeric()
                    ->required()
                    ->inputMode('decimal'),

                // Discount
                TextInput::make('discount')
                    ->label('Discount (%)')
                    ->numeric()
                    ->default(0)
                    ->suffix('%')
                    ->inputMode('decimal')
                    ->maxValue(100),
            ]);
    }
}
