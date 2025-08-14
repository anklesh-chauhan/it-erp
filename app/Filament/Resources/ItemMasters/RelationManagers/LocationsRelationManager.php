<?php

namespace App\Filament\Resources\ItemMasters\RelationManagers;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\AttachAction;
use Filament\Forms\Components\Select;
use Filament\Actions\EditAction;
use Filament\Actions\DetachAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\LocationMaster;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LocationsRelationManager extends RelationManager
{
    protected static string $relationship = 'locations';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('quantity') // Pivot field
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
                TextColumn::make('name')
                    ->label('Location Name'),
                TextColumn::make('pivot.quantity') // Display pivot field
                    ->label('Quantity'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                ->recordSelect(function () {
                    return self::getIndentedLocations();
                })
                ->preloadRecordSelect()
                ->form([
                    Select::make('recordId')
                    ->label('Location Name')
                    ->options(fn () => self::getIndentedLocations())
                    ->searchable()
                    ->required(),
                    TextInput::make('quantity')
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
            ->recordActions([
                EditAction::make(),
                DetachAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                    DeleteBulkAction::make(),
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
