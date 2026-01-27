<?php

namespace App\Filament\Resources\AccountMasters;

use App\Traits\HasSafeGlobalSearch;

use App\Filament\Actions\ApprovalAction;

use App\Filament\Actions\BulkApprovalAction;

use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use App\Filament\Resources\AccountMasters\RelationManagers\ContactDetailsRelationManager;
use App\Filament\Resources\AccountMasters\RelationManagers\AddressesRelationManager;
use App\Filament\Resources\AccountMasters\Pages\ListAccountMasters;
use App\Filament\Resources\AccountMasters\Pages\CreateAccountMaster;
use App\Filament\Resources\AccountMasters\Pages\EditAccountMaster;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AccountMasterResource\Pages;
use App\Models\AccountMaster;
use Filament\Forms;
use Filament\Resources\Resource;
use App\Filament\Resources\BaseResource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Traits\CreateAccountMasterTrait;
use App\Filament\Resources\AccountMasterBankDetailResource\RelationManagers\BankDetailRelationManager;
use App\Filament\Resources\AccountMasterCreditDetailResource\RelationManagers\CreditDetailRelationManager;
use App\Filament\Resources\AccountMasterGSTDetailResource\RelationManagers\GSTDetailRelationManager;
use App\Filament\Resources\AccountMasterStatutoryDetailResource\RelationManagers\StatutoryDetailRelationManager;

class AccountMasterResource extends BaseResource
{
    use HasSafeGlobalSearch;
    use CreateAccountMasterTrait;

    protected static ?string $model = AccountMaster::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static string | \UnitEnum | null $navigationGroup = 'Contacts & Companies';
    protected static ?int $navigationSort = 190;
    protected static ?string $navigationLabel = 'Account Masters';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                ...self::getCreateAccountMasterTraitFields()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('account_code')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('phone_number')
                    ->searchable(),
                TextColumn::make('industryType.name')
                    ->sortable(),
                TextColumn::make('region.name')
                    ->sortable(),
                TextColumn::make('commission')
                    ->money('usd'), // Adjust currency as needed
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
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
                ApprovalAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkApprovalAction::make(),
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            AddressesRelationManager::class,
            ContactDetailsRelationManager::class,
            BankDetailRelationManager::class,
            CreditDetailRelationManager::class,
            GSTDetailRelationManager::class,
            StatutoryDetailRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAccountMasters::route('/'),
            'create' => CreateAccountMaster::route('/create'),
            'edit' => EditAccountMaster::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([SoftDeletingScope::class]);
    }
}
