<?php

namespace App\Filament\Resources\CompanyMasterTypes;

use App\Filament\Actions\ApprovalAction;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\CompanyMasterTypes\Pages\ListCompanyMasterTypes;
use App\Filament\Resources\CompanyMasterTypes\Pages\CreateCompanyMasterType;
use App\Filament\Resources\CompanyMasterTypes\Pages\EditCompanyMasterType;
use App\Filament\Resources\CompanyMasterTypeResource\Pages;
use App\Filament\Resources\CompanyMasterTypeResource\RelationManagers;
use App\Models\CompanyMasterType;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CompanyMasterTypeResource extends Resource
{
    protected static ?string $model = CompanyMasterType::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static string | \UnitEnum | null $navigationGroup = 'Global Config';
    protected static ?string $navigationParentItem = 'Company Config';
    protected static ?int $navigationSort = 1001;
    protected static ?string $navigationLabel = 'Company Type';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
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
            'index' => ListCompanyMasterTypes::route('/'),
            'create' => CreateCompanyMasterType::route('/create'),
            'edit' => EditCompanyMasterType::route('/{record}/edit'),
        ];
    }
}
