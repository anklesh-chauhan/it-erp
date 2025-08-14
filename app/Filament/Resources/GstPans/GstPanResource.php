<?php

namespace App\Filament\Resources\GstPans;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\GstPans\Pages\ListGstPans;
use App\Filament\Resources\GstPans\Pages\CreateGstPan;
use App\Filament\Resources\GstPans\Pages\EditGstPan;
use App\Filament\Resources\GstPanResource\Pages;
use App\Filament\Resources\GstPanResource\RelationManagers;
use App\Models\GstPan;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GstPanResource extends Resource
{
    protected static ?string $model = GstPan::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static string | \UnitEnum | null $navigationGroup = 'Masters';
    protected static ?string $navigationParentItem = 'Comapany Master';
    protected static ?int $navigationSort = 200;
    protected static ?string $navigationLabel = 'GST & PAN Details';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('company_master_id')
                    ->numeric(),
                TextInput::make('company_id')
                    ->required()
                    ->numeric(),
                TextInput::make('address_id')
                    ->required()
                    ->numeric(),
                TextInput::make('pan_number')
                    ->required()
                    ->maxLength(255),
                TextInput::make('gst_number')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('company_master_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('company_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('address_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('pan_number')
                    ->searchable(),
                TextColumn::make('gst_number')
                    ->searchable(),
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
            'index' => ListGstPans::route('/'),
            'create' => CreateGstPan::route('/create'),
            'edit' => EditGstPan::route('/{record}/edit'),
        ];
    }
}
