<?php

namespace App\Filament\Resources\TransportModes;

use App\Filament\Actions\BulkApprovalAction;

use App\Traits\HasSafeGlobalSearch;

use App\Filament\Actions\ApprovalAction;
use App\Filament\Clusters\GlobalConfiguration\SalesMarketingConfigurationCluster;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\TransportModes\Pages\ListTransportModes;
use App\Filament\Resources\TransportModes\Pages\CreateTransportMode;
use App\Filament\Resources\TransportModes\Pages\EditTransportMode;
use App\Filament\Resources\TransportModeResource\Pages;
use App\Filament\Resources\TransportModeResource\RelationManagers;
use App\Models\TransportMode;
use Filament\Forms;
use Filament\Resources\Resource;
use App\Filament\Resources\BaseResource;
use Filament\Forms\Components\Toggle;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransportModeResource extends BaseResource
{
    use HasSafeGlobalSearch;
    protected static ?string $model = TransportMode::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $cluster = SalesMarketingConfigurationCluster::class;
    protected static ?int $navigationSort = 30;
    protected static ?string $navigationLabel = 'Transport Modes';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->required(),
                TextInput::make('code')->required()->unique(ignoreRecord: true),
                Toggle::make('is_distance_based'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('code'),
                IconColumn::make('is_distance_based')->boolean(),
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
            'index' => ListTransportModes::route('/'),
            'create' => CreateTransportMode::route('/create'),
            'edit' => EditTransportMode::route('/{record}/edit'),
        ];
    }
}
