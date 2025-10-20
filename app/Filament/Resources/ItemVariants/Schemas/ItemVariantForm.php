<?php

namespace App\Filament\Resources\ItemVariants\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ItemVariantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('item_master_id')
                    ->required()
                    ->numeric(),
                TextInput::make('variant_name')
                    ->required(),
                TextInput::make('sku')
                    ->label('SKU')
                    ->required(),
                TextInput::make('barcode'),
                TextInput::make('purchase_price')
                    ->numeric(),
                TextInput::make('selling_price')
                    ->numeric(),
                TextInput::make('tax_rate')
                    ->numeric(),
                TextInput::make('discount')
                    ->numeric(),
                TextInput::make('stock')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('unit_of_measurement_id')
                    ->numeric(),
                TextInput::make('packaging_type_id')
                    ->numeric(),
                DatePicker::make('expiry_date'),
            ]);
    }
}
