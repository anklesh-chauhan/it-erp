<?php

namespace App\Filament\Resources\AccountMasterCreditDetailResource\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Enums\AccountMasterCreditType;

class CreditDetailRelationManager extends RelationManager
{
    protected static string $relationship = 'creditDetail';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('credit_type')
                    ->options(AccountMasterCreditType::options())
                    ->required()
                    ->native(false)
                    ->searchable()
                    ->reactive(),
                TextInput::make('credit_days')
                    ->numeric()
                    ->label('Credit Days')
                    ->visible(fn (callable $get) => in_array($get('credit_type'), [AccountMasterCreditType::DAYS->value, AccountMasterCreditType::BOTH->value])),

                TextInput::make('credit_limit')
                    ->numeric()
                    ->label('Credit Limit')
                    ->visible(fn (callable $get) => in_array($get('credit_type'), [AccountMasterCreditType::LIMIT->value, AccountMasterCreditType::BOTH->value])),

                Toggle::make('credit_status')
                    ->required()
                    ->default('active'),
                DatePicker::make('credit_review_date'),
                Textarea::make('credit_terms')
                    ->columnSpanFull(),
                Textarea::make('remark')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Credit Type')
            ->columns([
                TextColumn::make('credit_type'),
                TextColumn::make('credit_days')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('credit_limit')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('credit_rating'),
                ToggleColumn::make('credit_status')
                    ->searchable(),
                TextColumn::make('credit_review_date')
                    ->date()
                    ->sortable(),
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
