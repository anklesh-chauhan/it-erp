<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TourPlanResource\Pages;
use App\Filament\Resources\TourPlanResource\RelationManagers;
use App\Models\TourPlan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TourPlanResource extends Resource
{
    protected static ?string $model = TourPlan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Sales & Marketing';
    protected static ?string $navigationParentItem = 'Daily Call Report';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Tour Plan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('plan_date')
                    ->required(),
                Forms\Components\TextInput::make('location')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('start_time')
                    ->required(),
                Forms\Components\TextInput::make('end_time')
                    ->required(),
                Forms\Components\TextInput::make('visit_purpose_id')
                    ->numeric(),
                Forms\Components\TextInput::make('target_customer')
                    ->maxLength(255),
                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('mode_of_transport')
                    ->maxLength(255),
                Forms\Components\TextInput::make('distance_travelled')
                    ->numeric(),
                Forms\Components\TextInput::make('travel_expenses')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('plan_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('location')
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_time'),
                Tables\Columns\TextColumn::make('end_time'),
                Tables\Columns\TextColumn::make('visit_purpose_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('target_customer')
                    ->searchable(),
                Tables\Columns\TextColumn::make('mode_of_transport')
                    ->searchable(),
                Tables\Columns\TextColumn::make('distance_travelled')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('travel_expenses')
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
            'index' => Pages\ListTourPlans::route('/'),
            'create' => Pages\CreateTourPlan::route('/create'),
            'edit' => Pages\EditTourPlan::route('/{record}/edit'),
        ];
    }
}
