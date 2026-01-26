<?php

namespace App\Filament\Resources\Employees\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Actions\AttachAction;
use Filament\Actions\EditAction;
use Filament\Actions\DetachAction;
use App\Models\Position;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;

class PositionsRelationManager extends RelationManager
{
    protected static string $relationship = 'positions';

    protected static ?string $title = 'Assigned Positions';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Position'),

                Tables\Columns\IconColumn::make('pivot.is_primary')
                    ->label('Primary')
                    ->boolean(),
            ])
            ->headerActions([
                AttachAction::make()
                    ->label('Assign Position')
                    ->preloadRecordSelect()
                    ->schema(fn (AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Toggle::make('is_primary')
                            ->label('Set as Primary Position')
                            // Logic: If there are 0 positions, default this toggle to true
                            ->default(fn () => $this->ownerRecord->positions()->count() === 0),
                    ])
                    ->mutateDataUsing(function (array $data) {
                        // If this is the very first record, force is_primary to true
                        // regardless of what the user clicked (safety check)
                        if ($this->ownerRecord->positions()->count() === 0) {
                            $data['is_primary'] = true;
                        }
                        return $data;
                    })
                    ->before(function (array $data) {
                        // Same exclusivity logic as before: if this is primary, uncheck others
                        if ($data['is_primary'] ?? false) {
                            $this->ownerRecord->positions()
                                ->wherePivot('is_primary', true)
                                ->updateExistingPivot(
                                    $this->ownerRecord->positions()->pluck('positions.id'),
                                    ['is_primary' => false]
                                );
                        }
                    }),
            ])
            ->actions([
                EditAction::make()
                    ->form([
                            Toggle::make('is_primary')
                                ->label('Primary Position'),
                        ])
                   ->before(function (array $data) {
                        // If checking "Primary", uncheck all others first
                        if ($data['is_primary'] ?? false) {
                            $this->ownerRecord->positions()
                                ->wherePivot('is_primary', true)
                                ->each(function ($position) {
                                    $this->ownerRecord->positions()->updateExistingPivot($position->id, [
                                        'is_primary' => false,
                                    ]);
                                });
                        }
                    }),

                DetachAction::make(),
            ]);
    }
}
