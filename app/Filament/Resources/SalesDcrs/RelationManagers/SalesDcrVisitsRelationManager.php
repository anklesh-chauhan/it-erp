<?php

namespace App\Filament\Resources\SalesDcrs\RelationManagers;

use App\Filament\Resources\Visits\Schemas\VisitForm;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SalesDcrVisitsRelationManager extends RelationManager
{
    protected static string $relationship = 'SalesDcrVisits';

    public function form(Schema $schema): Schema
    {
        return VisitForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Visits')
            ->columns([
                TextColumn::make('visit_date')
                    ->datetime('d-m-Y'),
                TextColumn::make('start_time')
                    ->datetime('H:i'),
                TextColumn::make('end_time')
                    ->datetime('H:i'),
                TextColumn::make('visit_type')
                    ->label('Type'),
                TextColumn::make('outcome.label')
                    ->label('Outcome'),

            ])
            ->filters([

            ])
            ->headerActions([
                // CreateAction::make(),
                // AssociateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                // DissociateAction::make(),
                // DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DissociateBulkAction::make(),
                    // DeleteBulkAction::make(),
                ]),
            ]);
    }
}
