<?php

namespace App\Filament\Resources\DailyAttendances\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use punches;

class PunchesRelationManager extends RelationManager
{
    protected static string $relationship = 'punches';

    protected static ?string $title = 'Punch Log';

    protected static ?string $recordTitleAttribute = 'punch_time';

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('punch_time')
            ->columns([
                TextColumn::make('punch_time')
                    ->label('Time')
                    ->time('H:i')
                    ->sortable(),

                TextColumn::make('punch_type')
                    ->badge()
                    ->label('Type')
                    ->colors([
                        'success' => 'in',
                        'danger' => 'out',
                    ])
                    ->formatStateUsing(fn ($state) => strtoupper($state)),

                TextColumn::make('source')
                    ->label('Source')
                    ->badge(),

                TextColumn::make('device_id')
                    ->label('Device'),

                TextColumn::make('location')
                    ->label('Location'),
            ])
            ->actions([])          // 🔒 No edit/delete
            ->headerActions([])    // 🔒 No create
            ->bulkActions([]);     // 🔒 No bulk delete
    }
}
