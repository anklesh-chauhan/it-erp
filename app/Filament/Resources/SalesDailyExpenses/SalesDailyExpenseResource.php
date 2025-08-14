<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SalesDailyExpenseResource\Pages;
use App\Filament\Resources\SalesDailyExpenseResource\RelationManagers;
use App\Models\SalesDailyExpense;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SalesDailyExpenseResource extends Resource
{
    protected static ?string $model = SalesDailyExpense::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Marketing';
    protected static ?int $navigationSort = 40;
    protected static ?string $navigationLabel = 'Daily Expense';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('serial_number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('expense_date')
                    ->required(),
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('transaction_date')
                    ->required(),
                Forms\Components\TextInput::make('category_type')
                    ->maxLength(255),
                Forms\Components\TextInput::make('category_id')
                    ->numeric(),
                Forms\Components\TextInput::make('expense_type_id')
                    ->numeric(),
                Forms\Components\TextInput::make('tour_plan_id')
                    ->numeric(),
                Forms\Components\TextInput::make('rate_amount')
                    ->numeric(),
                Forms\Components\TextInput::make('claim_amount')
                    ->numeric(),
                Forms\Components\TextInput::make('approved_amount')
                    ->numeric(),
                Forms\Components\TextInput::make('approver_id')
                    ->numeric(),
                Forms\Components\Textarea::make('remarks')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('serial_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('expense_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('transaction_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('expense_type_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tour_plan_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rate_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('claim_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('approved_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('approver_id')
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
                Tables\Columns\TextColumn::make('deleted_at')
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
            'index' => Pages\ListSalesDailyExpenses::route('/'),
            'create' => Pages\CreateSalesDailyExpense::route('/create'),
            'edit' => Pages\EditSalesDailyExpense::route('/{record}/edit'),
        ];
    }
}
