<?php

namespace App\Filament\Resources\TourPlans;

use App\Traits\HasSafeGlobalSearch;

use App\Filament\Actions\ApprovalAction;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\TourPlans\Pages\ListTourPlans;
use App\Filament\Resources\TourPlans\Pages\CreateTourPlan;
use App\Filament\Resources\TourPlans\Pages\EditTourPlan;
use App\Filament\Resources\TourPlanResource\Pages;
use App\Filament\Resources\TourPlanResource\RelationManagers;
use App\Models\TourPlan;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TourPlanResource extends Resource
{
    use HasSafeGlobalSearch;
    protected static ?string $model = TourPlan::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static string | \UnitEnum | null $navigationGroup = 'Sales & Marketing';
    protected static ?string $navigationParentItem = 'Daily Call Report';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Tour Plan';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                DatePicker::make('plan_date')
                    ->required(),
                TextInput::make('location')
                    ->required()
                    ->maxLength(255),
                TextInput::make('start_time')
                    ->required(),
                TextInput::make('end_time')
                    ->required(),
                TextInput::make('visit_purpose_id')
                    ->numeric(),
                TextInput::make('target_customer')
                    ->maxLength(255),
                Textarea::make('notes')
                    ->columnSpanFull(),
                TextInput::make('mode_of_transport')
                    ->maxLength(255),
                TextInput::make('distance_travelled')
                    ->numeric(),
                TextInput::make('travel_expenses')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('plan_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('location')
                    ->searchable(),
                TextColumn::make('start_time'),
                TextColumn::make('end_time'),
                TextColumn::make('visit_purpose_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('target_customer')
                    ->searchable(),
                TextColumn::make('mode_of_transport')
                    ->searchable(),
                TextColumn::make('distance_travelled')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('travel_expenses')
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
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                ApprovalAction::make(),
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
            'index' => ListTourPlans::route('/'),
            'create' => CreateTourPlan::route('/create'),
            'edit' => EditTourPlan::route('/{record}/edit'),
        ];
    }
}
