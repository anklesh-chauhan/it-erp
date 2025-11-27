<?php

namespace App\Filament\Resources\CompanyMasterBankDetails;

use App\Traits\HasSafeGlobalSearch;

use App\Filament\Actions\ApprovalAction;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\CompanyMasterBankDetails\Pages\ListCompanyMasterBankDetails;
use App\Filament\Resources\CompanyMasterBankDetails\Pages\CreateCompanyMasterBankDetail;
use App\Filament\Resources\CompanyMasterBankDetails\Pages\EditCompanyMasterBankDetail;
use App\Filament\Resources\CompanyMasterBankDetailResource\Pages;
use App\Filament\Resources\CompanyMasterBankDetailResource\RelationManagers;
use App\Models\CompanyMasterBankDetail;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CompanyMasterBankDetailResource extends Resource
{
    use HasSafeGlobalSearch;
    protected static ?string $model = CompanyMasterBankDetail::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static string | \UnitEnum | null $navigationGroup = 'Masters';
    protected static ?string $navigationParentItem = 'Comapany Master';
    protected static ?int $navigationSort = 200;
    protected static ?string $navigationLabel = 'Comapany Master Bank Details';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('company_master_id')
                    ->numeric(),
                TextInput::make('bank_name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('account_number')
                    ->required()
                    ->maxLength(255),
                TextInput::make('ifsc_code')
                    ->required()
                    ->maxLength(255),
                TextInput::make('name_in_bank')
                    ->required()
                    ->maxLength(255),
                Textarea::make('remarks')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('company_master_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('bank_name')
                    ->searchable(),
                TextColumn::make('account_number')
                    ->searchable(),
                TextColumn::make('ifsc_code')
                    ->searchable(),
                TextColumn::make('name_in_bank')
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
            'index' => ListCompanyMasterBankDetails::route('/'),
            'create' => CreateCompanyMasterBankDetail::route('/create'),
            'edit' => EditCompanyMasterBankDetail::route('/{record}/edit'),
        ];
    }
}
