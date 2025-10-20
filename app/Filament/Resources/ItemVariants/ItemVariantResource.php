<?php

namespace App\Filament\Resources\ItemVariants;

use App\Filament\Resources\ItemVariants\Pages\CreateItemVariant;
use App\Filament\Resources\ItemVariants\Pages\EditItemVariant;
use App\Filament\Resources\ItemVariants\Pages\ListItemVariants;
use App\Filament\Resources\ItemVariants\Schemas\ItemVariantForm;
use App\Filament\Resources\ItemVariants\Tables\ItemVariantsTable;
use App\Models\ItemVariant;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemVariantResource extends Resource
{
    protected static ?string $model = ItemVariant::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string | \UnitEnum | null $navigationGroup = 'Masters';
    protected static ?int $navigationSort = 199;
    protected static ?string $navigationLabel = 'Item Variants';

    public static function form(Schema $schema): Schema
    {
        return ItemVariantForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ItemVariantsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListItemVariants::route('/'),
            'create' => CreateItemVariant::route('/create'),
            'edit' => EditItemVariant::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
