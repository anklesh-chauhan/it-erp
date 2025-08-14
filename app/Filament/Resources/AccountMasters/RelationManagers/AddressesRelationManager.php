<?php

namespace App\Filament\Resources\AccountMasterResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\CreateAddressFormTrait;

class AddressesRelationManager extends RelationManager
{
    use CreateAddressFormTrait;

    protected static string $relationship = 'addresses';

    public function form(Form $form): Form
    {
        // Define the form schema for creating/editing addresses directly
        return $form
            ->schema([
                ...self::getCreateAddressFormFields(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('street')
            ->columns([
                Tables\Columns\TextColumn::make('typeMaster.name') // Updated to match relationship name
                    ->label('Type')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('street')
                    ->label('Street')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('city.name')
                    ->label('City')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('state.name')
                    ->label('State')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('country.name')
                    ->label('Country')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('pin_code')
                    ->label('Pin Code')
                    ->sortable()
                    ->searchable(),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('address_type')
                    ->relationship('typeMaster', 'name') // Updated to match relationship name
                    ->preload(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->after(function (RelationManager $livewire, $record) {
                        $accountMaster = $livewire->getOwnerRecord();
                        $company = \App\Models\Company::where('account_master_id', $accountMaster->id)->first();

                        if ($company) {
                            $record->company_id = $company->id;
                            $record->save();

                            \Filament\Notifications\Notification::make()
                                ->title('Address linked to Company')
                                ->success()
                                ->send();
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
