<?php

namespace App\Filament\Resources\VisitRoutes;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\VisitRoutes\Pages\ListVisitRoutes;
use App\Filament\Resources\VisitRoutes\Pages\CreateVisitRoute;
use App\Filament\Resources\VisitRoutes\Pages\EditVisitRoute;
use App\Filament\Resources\VisitRouteResource\Pages;
use App\Filament\Resources\VisitRouteResource\RelationManagers;
use App\Models\VisitRoute;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VisitRouteResource extends Resource
{
    protected static ?string $model = VisitRoute::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static string | \UnitEnum | null $navigationGroup = 'Sales & Marketing';
    protected static ?string $navigationParentItem = 'Daily Call Report';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Routes';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                DatePicker::make('route_date')
                    ->required(),
                TextInput::make('lead_id')
                    ->numeric(),
                TextInput::make('contact_detail_id')
                    ->numeric(),
                TextInput::make('company_id')
                    ->numeric(),
                TextInput::make('address_id')
                    ->numeric(),
                TextInput::make('description')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('route_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('lead_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('contact_detail_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('company_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('address_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('description')
                    ->searchable(),
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
            'index' => ListVisitRoutes::route('/'),
            'create' => CreateVisitRoute::route('/create'),
            'edit' => EditVisitRoute::route('/{record}/edit'),
        ];
    }
}
