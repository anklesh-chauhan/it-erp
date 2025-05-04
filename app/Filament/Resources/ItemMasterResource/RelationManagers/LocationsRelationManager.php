<?php

namespace App\Filament\Resources\ItemMasterResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\LocationMaster;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LocationsRelationManager extends RelationManager
{
    protected static string $relationship = 'locations';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('quantity') // Pivot field
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Location Name'),
                Tables\Columns\TextColumn::make('pivot.quantity') // Display pivot field
                    ->label('Quantity'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                ->recordSelect(function () {
                    return self::getIndentedLocations();
                })
                ->preloadRecordSelect()
                ->form([
                    Forms\Components\Select::make('recordId')
                    ->label('Location Name')
                    ->options(fn () => self::getIndentedLocations())
                    ->searchable()
                    ->required(),
                    Forms\Components\TextInput::make('quantity')
                        ->numeric()
                        ->minValue(1)
                        ->required(),
                ])
                ->action(function (array $data, RelationManager $livewire) {
                    $record = $livewire->getOwnerRecord(); // Get the parent record

                    $record->locations()->attach($data['recordId'], [
                        'quantity' => $data['quantity'],
                    ]);
                }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * Get a list of active locations with indented sublocations
     */
    protected static function getIndentedLocations(): array
    {
        // Fetch top-level active locations with their sublocations
        $locations = LocationMaster::with('subLocations')
            ->whereNull('parent_id')
            ->where('is_active', true) // Filter active locations
            ->get();

        $options = [];
        self::buildLocationOptions($locations, $options);

        return $options;
    }

    /**
     * Recursively build the options array with indentation
     */
    protected static function buildLocationOptions($locations, &$options, $prefix = '')
    {
        foreach ($locations as $location) {
            $options[$location->id] = $prefix . $location->name . ' [' . $location->location_code . ']';

            // Recursively include sublocations if they exist and are active
            if ($location->subLocations->isNotEmpty()) {
                $activeSubLocations = $location->subLocations->where('is_active', true);
                self::buildLocationOptions($activeSubLocations, $options, $prefix . '— ');
            }
        }
    }
}
