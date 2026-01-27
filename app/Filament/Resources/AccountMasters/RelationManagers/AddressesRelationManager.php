<?php

namespace App\Filament\Resources\AccountMasters\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\CreateAction;
use App\Models\Company;
use Filament\Actions\EditAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CreateAddressFormTrait;
use Illuminate\Database\Eloquent\Model;
use App\Models\Address;

class AddressesRelationManager extends RelationManager
{
    use CreateAddressFormTrait;

    protected static string $relationship = 'addresses';

    public function form(Schema $schema): Schema
    {
        // Define the form schema for creating/editing addresses directly
        return $schema
            ->components([
                ...self::getCreateAddressFormFields(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('street')
            ->columns([
                TextColumn::make('typeMaster.name') // Updated to match relationship name
                    ->label('Type')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('street')
                    ->label('Street')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('city.name')
                    ->label('City')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('state.name')
                    ->label('State')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('country.name')
                    ->label('Country')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('pin_code')
                    ->label('Pin Code')
                    ->sortable()
                    ->searchable(),

            ])
            ->filters([
                SelectFilter::make('address_type')
                    ->relationship('typeMaster', 'name') // Updated to match relationship name
                    ->preload(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->after(function (RelationManager $livewire, $record) {
                        $accountMaster = $livewire->getOwnerRecord();
                        $company = Company::where('account_master_id', $accountMaster->id)->first();

                        if ($company) {
                            $record->company_id = $company->id;
                            $record->save();

                            Notification::make()
                                ->title('Address linked to Company')
                                ->success()
                                ->send();
                        }
                        if (! $accountMaster || ! $record->pin_code) {
                            return;
                        }

                        $territoryId = \App\Services\TerritoryService::fromPinCode(
                            $record->pin_code
                        );

                        if ($territoryId && ! $accountMaster->territory_id) {
                            $accountMaster->update([
                                'territory_id' => $territoryId,
                            ]);
                        }
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                DetachAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
