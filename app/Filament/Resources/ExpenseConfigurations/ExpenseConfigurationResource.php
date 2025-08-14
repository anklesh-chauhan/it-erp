<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExpenseConfigurationResource\Pages;
use App\Filament\Resources\ExpenseConfigurationResource\RelationManagers;
use App\Models\ExpenseConfiguration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExpenseConfigurationResource extends Resource
{
    protected static ?string $model = ExpenseConfiguration::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Global Config';
    protected static ?int $navigationSort = 1004;
    protected static ?string $navigationLabel = 'Expense Config';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('category_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('expense_type_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('transport_mode_id')
                    ->numeric(),
                Forms\Components\TextInput::make('rate_per_km')
                    ->numeric(),
                Forms\Components\TextInput::make('fixed_expense')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('expense_type_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('transport_mode_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rate_per_km')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fixed_expense')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListExpenseConfigurations::route('/'),
            'create' => Pages\CreateExpenseConfiguration::route('/create'),
            'edit' => Pages\EditExpenseConfiguration::route('/{record}/edit'),
        ];
    }
}
