<?php

namespace App\Filament\Resources\ItemMasters;

use App\Traits\ItemMasterTrait;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\ItemMasters\RelationManagers\LocationsRelationManager;
use App\Filament\Resources\ItemMasters\RelationManagers\LeadsRelationManager;
use App\Filament\Resources\ItemMasters\RelationManagers\SuppliersRelationManager;
use App\Filament\Resources\ItemMasters\RelationManagers\VariantsRelationManager;
use App\Filament\Resources\ItemMasters\Pages\ListItemMasters;
use App\Filament\Resources\ItemMasters\Pages\CreateItemMaster;
use App\Filament\Resources\ItemMasters\Pages\EditItemMaster;
use App\Filament\Resources\ItemMasterResource\Pages;
use App\Filament\Resources\ItemMasterResource\RelationManagers;
use Filament\Forms;
use App\Models\ItemMaster;
use App\Models\Category;
use App\Models\NumberSeries;
use App\Models\LocationMaster;
use App\Models\PackagingType;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\Filter;

class ItemMasterResource extends Resource
{
    use ItemMasterTrait;

    protected static ?string $model = ItemMaster::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static string | \UnitEnum | null $navigationGroup = 'Masters';
    protected static ?int $navigationSort = 198;
    protected static ?string $navigationLabel = 'Item Master';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                ...self::getItemMasterTraitField(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('item_code')->label('Item Code')->sortable()->searchable(),
                TextColumn::make('item_name')->label('Item Name')->sortable()->searchable(),
                TextColumn::make('category.name')
                    ->label('Category')
                    ->formatStateUsing(function ($state, $record) {
                        if (!$record->category) {
                            return '-';
                        }

                        // Find the root parent of the category
                        $category = $record->category;
                        $rootCategory = $category;
                        while ($rootCategory->parent) {
                            $rootCategory = $rootCategory->parent;
                        }

                        // Fetch the hierarchy starting from the root
                        $categories = Category::with('subCategories')
                            ->where('id', $rootCategory->id)
                            ->get();

                        $formatted = Category::formatCategories($categories);
                        return $formatted[$record->category->id] ?? $record->category->name;
                    })
                    ->searchable(),

                TextColumn::make('brand.name')->label('Brand')->sortable(),
                TextColumn::make('storage_location')->label('Storage'),
                TextColumn::make('expiry_date')->label('Expiry Date')->date()->badge(),
                TextColumn::make('locations.name')
                    ->label('Locations')
                    ->sortable(),
            ])
            ->filters([
                Filter::make('expiry_date')->label('Expired Items')
                    ->query(fn ($query) => $query->whereDate('expiry_date', '<', now())),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            LocationsRelationManager::class,
            LeadsRelationManager::class,
            SuppliersRelationManager::class,
            VariantsRelationManager::class,
            // RelationManagers\SalesOrdersRelationManager::class,
            // RelationManagers\PurchaseOrdersRelationManager::class,
            // RelationManagers\PurchaseInvoicesRelationManager::class,
            // RelationManagers\SalesInvoicesRelationManager::class,
            // RelationManagers\StockTransfersRelationManager::class,
            // RelationManagers\StockAdjustmentsRelationManager::class,
            // RelationManagers\ItemStockRelationManager::class,
            // RelationManagers\ItemStockHistoryRelationManager::class,
            // RelationManagers\ItemStockTransferRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListItemMasters::route('/'),
            'create' => CreateItemMaster::route('/create'),
            'edit' => EditItemMaster::route('/{record}/edit'),
        ];
    }

    /**
     * Recursively format locations with indentation for sublocations.
     */
    public static function formatLocations($locations, $prefix = '')
    {
        $options = [];
        foreach ($locations as $location) {
            $options[$location->id] = $prefix . $location->name;
            if ($location->subLocations->count()) {
                $options += self::formatLocations($location->subLocations, $prefix . '-- ');
            }
        }
        return $options;
    }

    public static function formatCategories($categories, $prefix = '')
    {
        $options = [];
        foreach ($categories as $category) {
            $options[$category->id] = $prefix . $category->name;
            if ($category->subCategories->count()) {
                $options += self::formatCategories($category->subCategories, $prefix . '-- ');
            }
        }
        return $options;
    }
}
