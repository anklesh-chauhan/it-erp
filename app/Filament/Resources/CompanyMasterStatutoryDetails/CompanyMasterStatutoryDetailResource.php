<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyMasterStatutoryDetailResource\Pages;
use App\Filament\Resources\CompanyMasterStatutoryDetailResource\RelationManagers;
use App\Models\CompanyMasterStatutoryDetail;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CompanyMasterStatutoryDetailResource extends Resource
{
    protected static ?string $model = CompanyMasterStatutoryDetail::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Masters';
    protected static ?string $navigationParentItem = 'Comapany Master';
    protected static ?int $navigationSort = 200;
    protected static ?string $navigationLabel = 'Comapany Master Statutory Details';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('company_master_id')
                    ->numeric(),
                Forms\Components\TextInput::make('credit_days')
                    ->numeric(),
                Forms\Components\TextInput::make('credit_limit')
                    ->numeric(),
                Forms\Components\TextInput::make('cin')
                    ->maxLength(255),
                Forms\Components\TextInput::make('tds_parameters')
                    ->maxLength(255),
                Forms\Components\Toggle::make('is_tds_deduct')
                    ->required(),
                Forms\Components\Toggle::make('is_tds_compulsory')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company_master_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('credit_days')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('credit_limit')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cin')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tds_parameters')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_tds_deduct')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_tds_compulsory')
                    ->boolean(),
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
            'index' => Pages\ListCompanyMasterStatutoryDetails::route('/'),
            'create' => Pages\CreateCompanyMasterStatutoryDetail::route('/create'),
            'edit' => Pages\EditCompanyMasterStatutoryDetail::route('/{record}/edit'),
        ];
    }
}
