<?php

namespace App\Filament\Resources\VisitRouteTourPlans;

use App\Filament\Actions\BulkApprovalAction;

use App\Traits\HasSafeGlobalSearch;

use App\Filament\Actions\ApprovalAction;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\VisitRouteTourPlans\Pages\ListVisitRouteTourPlans;
use App\Filament\Resources\VisitRouteTourPlans\Pages\CreateVisitRouteTourPlan;
use App\Filament\Resources\VisitRouteTourPlans\Pages\EditVisitRouteTourPlan;
use App\Filament\Resources\VisitRouteTourPlanResource\Pages;
use App\Filament\Resources\VisitRouteTourPlanResource\RelationManagers;
use App\Models\VisitRouteTourPlan;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VisitRouteTourPlanResource extends Resource
{
    use HasSafeGlobalSearch;
    protected static ?string $model = VisitRouteTourPlan::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static string | \UnitEnum | null $navigationGroup = 'Global Config';
    protected static ?string $navigationParentItem = 'Sales & Marketing';
    protected static ?int $navigationSort = 1001;
    protected static ?string $navigationLabel = 'Visit Route & Tour Plan Mapping';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('visit_route_id')
                    ->required()
                    ->numeric(),
                TextInput::make('tour_plan_id')
                    ->required()
                    ->numeric(),
                TextInput::make('visit_order')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('visit_route_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tour_plan_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('visit_order')
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
                ApprovalAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    
                        BulkApprovalAction::make(),

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
            'index' => ListVisitRouteTourPlans::route('/'),
            'create' => CreateVisitRouteTourPlan::route('/create'),
            'edit' => EditVisitRouteTourPlan::route('/{record}/edit'),
        ];
    }
}
