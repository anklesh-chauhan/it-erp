<?php

namespace App\Traits;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Components\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use App\Models\ItemMaster;
use App\Models\Category;
use App\Models\NumberSeries;
use App\Models\PackagingType;
use App\Models\CompanyMaster;

trait ItemMasterTrait
{
    /**
     * Get common form fields for SalesDocument.
     *
     * @return array
     */
    public static function getItemMasterTraitField(): array
    {
        return [
            Forms\Components\Grid::make(3)
                ->schema([
                    TextInput::make('item_code')
                        ->label('Item Code')
                        ->default(fn () => NumberSeries::getNextNumber(ItemMaster::class))
                        ->disabled()
                        ->dehydrated(true),
                    TextInput::make('item_name')
                        ->label('Item Name')
                        ->required(),

                    Select::make('category_id')
                        ->label('Category')
                        ->options(fn () => Category::whereNull('parent_id')->with('subCategories')->get()->pipe(fn ($categories) => Category::formatCategories($categories)))
                        ->searchable()
                        ->preload()
                        ->required(),
                    Select::make('unit_of_measurement_id')
                        ->relationship('unitOfMeasurement', 'name')
                        ->label('Unit of Measurement'),
                    TextInput::make('hsn_code')
                        ->label('HSN Code'),
                    TextInput::make('tax_rate')
                        ->label('Tax Rate (%)')
                        ->numeric(),
                    Select::make('item_brand_id')
                        ->relationship('brand', 'name')
                        ->label('Brand'),

                    Textarea::make('description')
                        ->label('Description'),

                    Forms\Components\Section::make('More Details')
                        ->schema([
                            Forms\Components\Grid::make(4)
                                ->schema([
                                    TextInput::make('purchase_price')
                                        ->label('Purchase Price')
                                        ->numeric(),
                                    TextInput::make('selling_price')
                                        ->label('Selling Price')
                                        ->numeric(),
                                    TextInput::make('discount')
                                        ->label('Discount (%)')
                                        ->numeric(),
                                    TextInput::make('opening_stock')
                                        ->label('Opening Stock')
                                        ->numeric(),
                                    TextInput::make('minimum_stock_level')
                                        ->label('Minimum Stock Level')
                                        ->numeric(),
                                    TextInput::make('reorder_quantity')
                                        ->label('Reorder Quantity')
                                        ->numeric(),
                                    TextInput::make('lead_time')
                                        ->label('Lead Time')
                                        ->numeric(),
                                    TextInput::make('barcode')
                                        ->label('Barcode'),
                                    DatePicker::make('expiry_date')
                                        ->label('Expiry Date'),
                                    Select::make('packaging_type_id')
                                        ->label('Packaging Type')
                                        ->options(PackagingType::pluck('name', 'id'))
                                        ->searchable()
                                        ->preload()
                                        ->nullable(),
                                    TextInput::make('per_packing_qty')
                                        ->label('Per Packing Quantity')
                                        ->numeric(),
                                ]),
                        ])
                        ->collapsed()
                ]),

        ];
    }
}
