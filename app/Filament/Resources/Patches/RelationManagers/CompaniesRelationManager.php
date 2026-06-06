<?php

namespace App\Filament\Resources\Patches\RelationManagers;

use App\Models\AccountMaster;
use App\Models\CityPinCode;
use App\Models\Territory;
use Filament\Actions\Action;
use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CompaniesRelationManager extends RelationManager
{
    protected static string $relationship = 'companies';

    protected static ?string $title = 'Assigned Customers';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('account_code')
                    ->label('Customer Code')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('typeMaster.name')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn ($state, $record) => $record->typeMaster?->parent?->name ?? $record->typeMaster?->name ?? 'Customer'
                    )
                    ->color('primary'),

                TextInputColumn::make('sequence_no')
                    ->label('Sequence No')
                    ->rules(['nullable', 'integer', 'min:1'])
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->updateStateUsing(function ($record, $state) {
                        $record->pivot->update([
                            'sequence_no' => $state,
                        ]);
                    }),

                TextColumn::make('distance_km')
                    ->label('Distance (km)')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('addresses.areaTown.area_town')
                    ->label('Primary Area/Town')
                    ->getStateUsing(function ($record) {
                        // Locate the primary address record
                        $primaryAddress = $record->addresses->firstWhere('is_primary', true);

                        // Return the area/town name or a dash if not found
                        return $primaryAddress?->areaTown?->area_town ?? '-';
                    })
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('addresses.areaTown.pin_code')
                    ->label('Pin Code(s)')
                    ->getStateUsing(fn ($record) => $record->addresses->pluck('areaTown.pin_code')->unique()->implode(', '))
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('typeMaster')
                    ->relationship('typeMaster', 'name')
                    ->multiple()
                    ->preload(),
            ])
            ->headerActions([
                Action::make('addAll')
                    ->label('Add All Customers from Territory')
                    ->icon('heroicon-o-plus-circle')
                    ->color('success')
                    ->action(function () {
                        $patch = $this->ownerRecord;

                        if (! $patch->territory_id) {
                            Notification::make()->title('No territory selected')->danger()->send();

                            return;
                        }

                        $pinCodes = $patch->city_pin_code_id
                            ? CityPinCode::where('id', $patch->city_pin_code_id)->pluck('pin_code')
                            : ($patch->territory?->cityPinCodes?->pluck('pin_code') ?? collect());

                        if ($pinCodes->isEmpty()) {
                            Notification::make()->title('No pin codes found')->warning()->send();

                            return;
                        }

                        $companyIds = AccountMaster::query()
                            ->whereHas('addresses', fn ($q) => $q->whereIn('pin_code', $pinCodes))
                            ->whereHas('typeMaster', fn ($q) => $q->where('name', 'Customer')
                                ->orWhereHas('parent', fn ($q) => $q->where('name', 'Customer'))
                            )
                            ->pluck('id');

                        $patch->companies()->syncWithoutDetaching($companyIds);

                        Notification::make()
                            ->title($companyIds->count().' companies added')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation()
                    ->modalDescription('This will add all eligible customers from the current territory/pin code without removing existing ones.'),

                AttachAction::make()
                    ->label('Add Customer')
                    ->multiple()
                    ->icon('heroicon-o-plus-circle')
                    ->modalHeading('Attach Customer to Patch')
                    ->modalSubmitActionLabel('Attach')
                    ->form(fn (AttachAction $action): array => [
                        Select::make('recordId')
                            ->label('Customer')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->options(function () {
                                // Use $this->ownerRecord instead of trying to access $action
                                $patch = $this->ownerRecord;

                                if (! $patch?->territory_id) {
                                    return [];
                                }

                                // Resolve pin codes
                                $pinCodes = collect();

                                if ($patch->city_pin_code_id) {
                                    $pinCodes = CityPinCode::where('id', $patch->city_pin_code_id)
                                        ->pluck('pin_code');
                                } else {
                                    $territory = Territory::with('cityPinCodes')
                                        ->find($patch->territory_id);

                                    $pinCodes = $territory?->cityPinCodes?->pluck('pin_code') ?? collect();
                                }

                                if ($pinCodes->isEmpty()) {
                                    return [];
                                }

                                return AccountMaster::query()
                                    ->whereHas('addresses', fn ($q) => $q->whereIn('pin_code', $pinCodes))
                                    ->whereHas('typeMaster', fn ($q) => $q->where('name', 'Customer')
                                        ->orWhereHas('parent', fn ($q) => $q->where('name', 'Customer'))
                                    )
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->getOptionLabelFromRecordUsing(fn (AccountMaster $record) => ($record->typeMaster?->parent?->name ?? $record->typeMaster?->name ?? 'Customer')
                                .' – '.$record->name
                            ),
                    ])
                    ->preloadRecordSelect()
                    ->action(function (array $data, $livewire) {
                        $livewire->ownerRecord->companies()->attach($data['recordId']);
                        Notification::make()
                            ->title('Company attached successfully')
                            ->success()
                            ->send();
                    }),
            ])
            ->actions([
                DetachAction::make()
                    ->label('Remove')
                    ->icon('heroicon-o-trash')
                    ->action(function ($record, $livewire) {
                        $livewire->ownerRecord->companies()->detach($record);
                        Notification::make()
                            ->title('Company removed from patch')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                DetachBulkAction::make()
                    ->label('Remove Selected')
                    ->action(function ($records, $livewire) {
                        $livewire->ownerRecord->companies()->detach($records);
                        Notification::make()
                            ->title('Selected companies removed')
                            ->success()
                            ->send();
                    }),
            ]);
    }
}
