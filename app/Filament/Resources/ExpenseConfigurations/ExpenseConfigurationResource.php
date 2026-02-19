<?php

namespace App\Filament\Resources\ExpenseConfigurations;

use App\Filament\Resources\ExpenseConfigurations\Pages\CreateExpenseConfiguration;
use App\Filament\Resources\ExpenseConfigurations\Pages\EditExpenseConfiguration;
use App\Filament\Resources\ExpenseConfigurations\Pages\ListExpenseConfigurations;
use App\Filament\Resources\ExpenseConfigurations\Schemas\ExpenseConfigurationForm;
use App\Filament\Resources\ExpenseConfigurations\Tables\ExpenseConfigurationsTable;
use App\Models\ExpenseConfiguration;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExpenseConfigurationResource extends Resource
{
    protected static ?string $model = ExpenseConfiguration::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'ExpenseConfiguration';

    public static function form(Schema $schema): Schema
    {
        return ExpenseConfigurationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExpenseConfigurationsTable::configure($table);
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
            'index' => ListExpenseConfigurations::route('/'),
            'create' => CreateExpenseConfiguration::route('/create'),
            'edit' => EditExpenseConfiguration::route('/{record}/edit'),
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
