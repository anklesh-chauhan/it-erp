<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SalesDcrResource\Pages;
use App\Filament\Resources\SalesDcrResource\RelationManagers;
use App\Models\SalesDcr;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SalesDcrResource extends Resource
{
    protected static ?string $model = SalesDcr::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Marketing';
    protected static ?int $navigationSort = 30;
    protected static ?string $navigationLabel = 'Daily Call Report';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date')
                    ->required(),
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('jointwork_user_ids'),
                Forms\Components\TextInput::make('visit_type_id')
                    ->numeric(),
                Forms\Components\TextInput::make('tour_plan_id')
                    ->numeric(),
                Forms\Components\TextInput::make('visit_route_ids'),
                Forms\Components\TextInput::make('category_type')
                    ->maxLength(255),
                Forms\Components\TextInput::make('category_id')
                    ->numeric(),
                Forms\Components\TextInput::make('expense_total')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('visit_type_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tour_plan_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('expense_total')
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
            'index' => Pages\ListSalesDcrs::route('/'),
            'create' => Pages\CreateSalesDcr::route('/create'),
            'edit' => Pages\EditSalesDcr::route('/{record}/edit'),
        ];
    }
}
