<?php

namespace App\Filament\Resources\AccountMasterCreditDetailResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Enums\AccountMasterCreditType;

class CreditDetailRelationManager extends RelationManager
{
    protected static string $relationship = 'creditDetail';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('credit_type')
                    ->options(AccountMasterCreditType::options())
                    ->required()
                    ->native(false)
                    ->searchable()
                    ->reactive(),
                Forms\Components\TextInput::make('credit_days')
                    ->numeric()
                    ->label('Credit Days')
                    ->visible(fn (callable $get) => in_array($get('credit_type'), [AccountMasterCreditType::DAYS->value, AccountMasterCreditType::BOTH->value])),

                Forms\Components\TextInput::make('credit_limit')
                    ->numeric()
                    ->label('Credit Limit')
                    ->visible(fn (callable $get) => in_array($get('credit_type'), [AccountMasterCreditType::LIMIT->value, AccountMasterCreditType::BOTH->value])),

                Forms\Components\Toggle::make('credit_status')
                    ->required()
                    ->default('active'),
                Forms\Components\DatePicker::make('credit_review_date'),
                Forms\Components\Textarea::make('credit_terms')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('remark')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Credit Type')
            ->columns([
                Tables\Columns\TextColumn::make('credit_type'),
                Tables\Columns\TextColumn::make('credit_days')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('credit_limit')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('credit_rating'),
                Tables\Columns\ToggleColumn::make('credit_status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('credit_review_date')
                    ->date()
                    ->sortable(),
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
