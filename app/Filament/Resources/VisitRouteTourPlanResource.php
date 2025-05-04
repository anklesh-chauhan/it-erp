<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VisitRouteTourPlanResource\Pages;
use App\Filament\Resources\VisitRouteTourPlanResource\RelationManagers;
use App\Models\VisitRouteTourPlan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VisitRouteTourPlanResource extends Resource
{
    protected static ?string $model = VisitRouteTourPlan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Global Config';
    protected static ?string $navigationParentItem = 'Sales & Marketing';
    protected static ?int $navigationSort = 1001;
    protected static ?string $navigationLabel = 'Visit Route & Tour Plan Mapping';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('visit_route_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('tour_plan_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('visit_order')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('visit_route_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tour_plan_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('visit_order')
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
            'index' => Pages\ListVisitRouteTourPlans::route('/'),
            'create' => Pages\CreateVisitRouteTourPlan::route('/create'),
            'edit' => Pages\EditVisitRouteTourPlan::route('/{record}/edit'),
        ];
    }
}
