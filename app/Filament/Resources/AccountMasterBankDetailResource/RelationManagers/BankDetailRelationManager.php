<?php

namespace App\Filament\Resources\AccountMasterBankDetailResource\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BankDetailRelationManager extends RelationManager
{
    protected static string $relationship = 'bankDetail';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('bank_name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('bank_account_number')
                    ->required()
                    ->maxLength(255),
                TextInput::make('bank_account_name')
                    ->maxLength(255),
                TextInput::make('bank_account_ifsc_code')
                    ->maxLength(255),
                TextInput::make('bank_account_branch')
                        ->maxLength(255),
                TextInput::make('bank_account_swift_code')
                    ->maxLength(255),

                // Show more toggle
                Toggle::make('show_more')
                    ->label('Show More Details')
                    ->inline(false)
                    ->reactive(),
                Section::make('More Bank Details')
                ->schema([
                    Grid::make(4)
                        ->schema([
                        TextInput::make('bank_account_iban')
                            ->maxLength(255),
                        TextInput::make('bank_account_bic')
                            ->maxLength(255),
                        TextInput::make('bank_account_phone')
                            ->tel()
                            ->maxLength(255),
                        TextInput::make('bank_account_email')
                            ->email()
                            ->maxLength(255),
                        TextInput::make('bank_account_address')
                            ->maxLength(255),
                        TextInput::make('bank_account_city')
                            ->maxLength(255),
                        TextInput::make('bank_account_state')
                            ->maxLength(255),
                        TextInput::make('bank_account_country')
                            ->required()
                            ->maxLength(255)
                            ->default('India'),
                        TextInput::make('bank_account_zip')
                            ->maxLength(255),
                        TextInput::make('bank_account_tax_id')
                            ->maxLength(255),
                        TextInput::make('bank_account_micr_code')
                            ->maxLength(255),
                        TextInput::make('bank_account_rtgs_code')
                            ->maxLength(255),
                        TextInput::make('bank_account_ecs_code')
                            ->maxLength(255),
                        TextInput::make('bank_account_code')
                            ->maxLength(255),
                        TextInput::make('bank_account_type')
                            ->maxLength(255),
                        TextInput::make('bank_account_currency')
                            ->required()
                            ->maxLength(255)
                            ->default('INR'),
                    ])->columnSpanFull(),
                ])
                ->columnSpanFull()
                ->visible(fn (Get $get) => $get('show_more')),

                Toggle::make('bank_account_status')
                    ->required()
                    ->default('active'),
                Textarea::make('remark')
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
                TextColumn::make('bank_name')
                    ->searchable(),
                TextColumn::make('bank_account_number')
                    ->searchable(),
                TextColumn::make('bank_account_name')
                    ->searchable(),
                TextColumn::make('bank_account_ifsc_code')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
