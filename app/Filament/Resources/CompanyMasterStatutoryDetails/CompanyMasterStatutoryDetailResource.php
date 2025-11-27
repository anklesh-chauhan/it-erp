<?php

namespace App\Filament\Resources\CompanyMasterStatutoryDetails;

use App\Filament\Actions\ApprovalAction;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\CompanyMasterStatutoryDetails\Pages\ListCompanyMasterStatutoryDetails;
use App\Filament\Resources\CompanyMasterStatutoryDetails\Pages\CreateCompanyMasterStatutoryDetail;
use App\Filament\Resources\CompanyMasterStatutoryDetails\Pages\EditCompanyMasterStatutoryDetail;
use App\Filament\Resources\CompanyMasterStatutoryDetailResource\Pages;
use App\Filament\Resources\CompanyMasterStatutoryDetailResource\RelationManagers;
use App\Models\CompanyMasterStatutoryDetail;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CompanyMasterStatutoryDetailResource extends Resource
{
    protected static ?string $model = CompanyMasterStatutoryDetail::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static string | \UnitEnum | null $navigationGroup = 'Masters';
    protected static ?string $navigationParentItem = 'Comapany Master';
    protected static ?int $navigationSort = 200;
    protected static ?string $navigationLabel = 'Comapany Master Statutory Details';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('company_master_id')
                    ->numeric(),
                TextInput::make('credit_days')
                    ->numeric(),
                TextInput::make('credit_limit')
                    ->numeric(),
                TextInput::make('cin')
                    ->maxLength(255),
                TextInput::make('tds_parameters')
                    ->maxLength(255),
                Toggle::make('is_tds_deduct')
                    ->required(),
                Toggle::make('is_tds_compulsory')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('company_master_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('credit_days')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('credit_limit')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('cin')
                    ->searchable(),
                TextColumn::make('tds_parameters')
                    ->searchable(),
                IconColumn::make('is_tds_deduct')
                    ->boolean(),
                IconColumn::make('is_tds_compulsory')
                    ->boolean(),
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
            'index' => ListCompanyMasterStatutoryDetails::route('/'),
            'create' => CreateCompanyMasterStatutoryDetail::route('/create'),
            'edit' => EditCompanyMasterStatutoryDetail::route('/{record}/edit'),
        ];
    }
}
