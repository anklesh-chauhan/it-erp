<?php

namespace App\Filament\Resources\AccountMasterBankDetailResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BankDetailRelationManager extends RelationManager
{
    protected static string $relationship = 'bankDetail';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('bank_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('bank_account_number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('bank_account_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('bank_account_ifsc_code')
                    ->maxLength(255),
                Forms\Components\TextInput::make('bank_account_swift_code')
                    ->maxLength(255),

                // Show more toggle
                Forms\Components\Toggle::make('show_more')
                    ->label('Show More Details')
                    ->inline(false)
                    ->reactive(),
                Forms\Components\Section::make('More Bank Details')
                ->schema([
                    Forms\Components\TextInput::make('bank_account_branch')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('bank_account_iban')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('bank_account_bic')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('bank_account_phone')
                        ->tel()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('bank_account_email')
                        ->email()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('bank_account_address')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('bank_account_city')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('bank_account_state')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('bank_account_country')
                        ->required()
                        ->maxLength(255)
                        ->default('India'),
                    Forms\Components\TextInput::make('bank_account_zip')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('bank_account_tax_id')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('bank_account_micr_code')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('bank_account_rtgs_code')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('bank_account_ecs_code')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('bank_account_code')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('bank_account_type')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('bank_account_currency')
                        ->required()
                        ->maxLength(255)
                        ->default('INR'),
                ])
                ->visible(fn (Forms\Get $get) => $get('show_more')),

                Forms\Components\Toggle::make('bank_account_status')
                    ->required()
                    ->default('active'),
                Forms\Components\Textarea::make('remark')
                    ->columnSpanFull(),
                // Forms\Components\Select::make('account_master_id')
                //     ->relationship('accountMaster', 'name')
                //     ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('bank_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bank_account_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bank_account_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bank_account_ifsc_code')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
