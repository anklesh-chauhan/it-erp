<?php

namespace App\Filament\Resources\SalesDcrExpenses;

use App\Filament\Resources\SalesDcrExpenses\Pages\CreateSalesDcrExpense;
use App\Filament\Resources\SalesDcrExpenses\Pages\EditSalesDcrExpense;
use App\Filament\Resources\SalesDcrExpenses\Pages\ListSalesDcrExpenses;
use App\Filament\Resources\SalesDcrExpenses\RelationManagers\SalesDcrExpenseItemsRelationManager;
use App\Filament\Resources\SalesDcrExpenses\Schemas\SalesDcrExpenseForm;
use App\Filament\Resources\SalesDcrExpenses\Tables\SalesDcrExpensesTable;
use App\Models\SalesDcrExpense;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SalesDcrExpenseResource extends Resource
{
    protected static ?string $model = SalesDcrExpense::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'SalesDcrExpense';

    public static function form(Schema $schema): Schema
    {
        return SalesDcrExpenseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SalesDcrExpensesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            SalesDcrExpenseItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSalesDcrExpenses::route('/'),
            'create' => CreateSalesDcrExpense::route('/create'),
            'edit' => EditSalesDcrExpense::route('/{record}/edit'),
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
