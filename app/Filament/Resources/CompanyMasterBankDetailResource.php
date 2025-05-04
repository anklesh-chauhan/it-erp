<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyMasterBankDetailResource\Pages;
use App\Filament\Resources\CompanyMasterBankDetailResource\RelationManagers;
use App\Models\CompanyMasterBankDetail;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CompanyMasterBankDetailResource extends Resource
{
    protected static ?string $model = CompanyMasterBankDetail::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Masters';
    protected static ?string $navigationParentItem = 'Comapany Master';
    protected static ?int $navigationSort = 200;
    protected static ?string $navigationLabel = 'Comapany Master Bank Details';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('company_master_id')
                    ->numeric(),
                Forms\Components\TextInput::make('bank_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('account_number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('ifsc_code')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name_in_bank')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('remarks')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company_master_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bank_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('account_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ifsc_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name_in_bank')
                    ->searchable(),
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
            'index' => Pages\ListCompanyMasterBankDetails::route('/'),
            'create' => Pages\CreateCompanyMasterBankDetail::route('/create'),
            'edit' => Pages\EditCompanyMasterBankDetail::route('/{record}/edit'),
        ];
    }
}
