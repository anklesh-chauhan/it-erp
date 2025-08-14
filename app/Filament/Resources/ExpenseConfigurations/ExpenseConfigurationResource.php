<?php

namespace App\Filament\Resources\ExpenseConfigurations;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\ExpenseConfigurations\Pages\ListExpenseConfigurations;
use App\Filament\Resources\ExpenseConfigurations\Pages\CreateExpenseConfiguration;
use App\Filament\Resources\ExpenseConfigurations\Pages\EditExpenseConfiguration;
use App\Filament\Resources\ExpenseConfigurationResource\Pages;
use App\Filament\Resources\ExpenseConfigurationResource\RelationManagers;
use App\Models\ExpenseConfiguration;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExpenseConfigurationResource extends Resource
{
    protected static ?string $model = ExpenseConfiguration::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static string | \UnitEnum | null $navigationGroup = 'Global Config';
    protected static ?int $navigationSort = 1004;
    protected static ?string $navigationLabel = 'Expense Config';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('category_id')
                    ->required()
                    ->numeric(),
                TextInput::make('expense_type_id')
                    ->required()
                    ->numeric(),
                TextInput::make('transport_mode_id')
                    ->numeric(),
                TextInput::make('rate_per_km')
                    ->numeric(),
                TextInput::make('fixed_expense')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('expense_type_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('transport_mode_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('rate_per_km')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('fixed_expense')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
}
