<?php

namespace App\Filament\Resources\AccountMasterGSTDetailResource\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Models\Address;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GstDetailRelationManager extends RelationManager
{
    protected static string $relationship = 'gstDetail';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('gst_number')
                    ->required()
                    ->maxLength(255),

                Select::make('address_id')
                    ->label('Registered Address')
                    ->required()
                    // Dynamically fetch addresses from the parent AccountMaster record
                    ->options(function (RelationManager $livewire) {
                        $accountMaster = $livewire->ownerRecord;

                        // Retrieve addresses, eager loading the 'city' relationship
                        // to prevent N+1 query issues and access the city name.
                        $addresses = $accountMaster->addresses()->with('city')->get();

                        return $addresses
                            ->mapWithKeys(function ($address) {
                                $cityName = $address->city->name ?? 'City N/A';
                                $stateName = $address->state->name ?? 'State N/A';
                                return [$address->id => $address->street . ', ' . $cityName.', '.$stateName];
                            })
                            ->toArray();
                    })
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(function (callable $set, $state) {
                        if ($state) {
                            // Find the selected address with its related state data
                            $address = Address::with('state')->find($state);
                            $stateName = $address->state->name ?? null;
                            $stateCode = $address->state->gst_code ?? null;

                            if ($stateName) {
                                // Set the state_name field
                                $set('state_name', $stateName);
                            }
                            if ($stateCode) {
                                // Set the state_code field
                                $set('state_code', $stateCode); // <-- Sets the state_code
                            }
                        } else {
                            // Clear fields if the selection is reset
                            $set('state_name', null);
                            $set('state_code', null);
                        }
                    })
                    ->preload()
                    ->columnSpan('full'), // Spans full width of the form
                
                TextInput::make('state_name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('state_code')
                    ->required()
                    ->maxLength(255),
                TextInput::make('gst_type')
                    ->required()
                    ->maxLength(255),
                Toggle::make('gst_status')
                    ->required()
                    ->default('active'),
                TextInput::make('pan_number')
                    ->maxLength(255),
                Textarea::make('remark')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('gst_number')
                    ->searchable(),
                TextColumn::make('state_name')
                    ->searchable(),
                TextColumn::make('state_code')
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
