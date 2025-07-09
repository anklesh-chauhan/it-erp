<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PatchResource\Pages;
use App\Models\Patch;
use App\Models\Company;
use App\Models\CityPinCode;
use App\Models\ContactDetail; // Renamed for clarity in this context
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder; // Keep if you add custom queries below

class PatchResource extends Resource
{
    protected static ?string $model = Patch::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // Added a label for better readability in the navigation
    protected static ?string $navigationLabel = 'Patches';

    // Optional: Add a slug for cleaner URLs if needed
    // protected static ?string $slug = 'patches';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->helperText('The unique name of the patch.'),

                TextInput::make('code')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->helperText('A unique code for the patch.'),

                Select::make('territory_id')
                    ->relationship(name: 'territory', titleAttribute: 'name', modifyQueryUsing: function (Builder $query) {
                        $user = Auth::user();
                        // Ensure user has an employee and employment detail before filtering
                        if ($user && $user->employee && $user->employee->employmentDetail) {
                            $organizationalUnitId = $user->employee->employmentDetail->organizational_unit_id;
                            // Filter territories based on the user's organizational unit
                            $query->whereHas('organizationalUnits', function ($subQuery) use ($organizationalUnitId) {
                                $subQuery->where('organizational_units.id', $organizationalUnitId);
                            });
                        } else {
                            // If no organizational unit, return no territories to prevent unauthorized access
                            $query->whereRaw('1 = 0');
                        }
                    })
                    ->required()
                    ->preload() // Eager load options for better performance
                    ->searchable()
                    ->live() // Crucial for dynamically updating the 'patchables' field
                    ->label('Territory')
                    ->placeholder('Select a Territory')
                    ->columnSpan(1), // Explicitly set column span for clarity

                // New field for City Pin Code, dependent on Territory selection
                Select::make('city_pin_code_id')
                    ->label('City Pin Code')
                    ->placeholder('Select a City Pin Code')
                    ->options(function (Get $get) {
                        $selectedTerritoryId = $get('territory_id');
                        if (!$selectedTerritoryId) {
                            return []; // No territory selected, no pin codes
                        }
                        // Fetch CityPinCodes related to the selected territory
                        return CityPinCode::whereHas('territories', function ($query) use ($selectedTerritoryId) {
                            $query->where('territories.id', $selectedTerritoryId);
                        })->pluck('pin_code', 'id')->toArray();
                    })
                    ->searchable()
                    ->preload()
                    ->nullable() // Allow null if a territory doesn't have a specific pin code requirement initially
                    ->live() // Make this live to update 'patchables' further
                    ->columnSpan(1),

                Textarea::make('description')
                    ->nullable()
                    ->maxLength(65535)
                    ->columnSpanFull()
                    ->rows(3) // Provide a reasonable default height
                    ->helperText('A brief description of the patch.'),

                ColorPicker::make('color')
                    ->nullable()
                    ->label('Patch Color')
                    ->helperText('Choose a color to visually represent the patch.')
                    ->columnSpanFull(),

                Select::make('patchables')
                    ->label('Assigned Companies or Contacts')
                    ->multiple()
                    ->searchable()
                    ->preload(false) // Keep as false for dynamic loading based on territory/pin code
                    ->options(function (Get $get) {
                        $selectedTerritoryId = $get('territory_id');
                        $selectedCityPinCodeId = $get('city_pin_code_id');
                
                        $relevantPinCodes = collect();
                
                        // Prioritize specific City Pin Code if selected, otherwise use territory
                        if ($selectedCityPinCodeId) {
                            $relevantPinCodes = CityPinCode::where('id', $selectedCityPinCodeId)->pluck('pin_code');
                        } elseif ($selectedTerritoryId) {
                            $relevantPinCodes = CityPinCode::whereHas('territories', function ($query) use ($selectedTerritoryId) {
                                $query->where('territories.id', $selectedTerritoryId);
                            })->pluck('pin_code');
                        }
                
                        // If no relevant pin codes, return empty options
                        if ($relevantPinCodes->isEmpty()) {
                            return [];
                        }
                
                        $companies = Company::whereHas('addresses', function ($query) use ($relevantPinCodes) {
                            $query->whereIn('pin_code', $relevantPinCodes);
                        })->get()
                        ->mapWithKeys(fn (Company $company) => ["company_{$company->id}" => "Company: {$company->name}"]);
                
                        $contacts = ContactDetail::where(function ($query) use ($relevantPinCodes) {
                            $query->whereHas('addresses', function ($subQuery) use ($relevantPinCodes) {
                                $subQuery->whereIn('pin_code', $relevantPinCodes);
                            })->orWhereHas('company.addresses', function ($subQuery) use ($relevantPinCodes) {
                                $subQuery->whereIn('pin_code', $relevantPinCodes);
                            });
                        })->get()
                        ->mapWithKeys(fn (ContactDetail $contact) => ["contact_{$contact->id}" => "Contact: {$contact->name} ({$contact->email})"]);
                
                        return array_merge(
                            $companies->toArray(),
                            $contacts->toArray()
                        );
                    })
                    ->getOptionLabelFromRecordUsing(function ($value) {
                        // This method is primarily for displaying selected options *after* they've been saved.
                        [$type, $id] = explode('_', $value);
                        
                        if ($type === 'company') {
                            $company = Company::find($id);
                            return $company ? "Company: {$company->name}" : 'Unknown Company';
                        }
                        $contact = ContactDetail::find($id);
                        return $contact ? "Contact: {$contact->name} ({$contact->email})" : 'Unknown Contact';
                    })
                    ->afterStateUpdated(function ($state, $set, $get) {
                        $patch = Patch::find($get('id'));
                        if ($patch) {
                            $companyIds = [];
                            $contactIds = [];
                            
                            foreach ($state as $value) {
                                [$type, $id] = explode('_', $value);
                                if ($type === 'company') {
                                    $companyIds[] = $id;
                                } elseif ($type === 'contact') {
                                    $contactIds[] = $id;
                                }
                            }
                            // Detach all current patchables
                            $patch->companies()->detach();
                            $patch->contacts()->detach();

                            // Attach selected companies
                            $companies = \App\Models\Company::whereIn('id', $companyIds)->get();
                            foreach ($companies as $company) {
                                $patch->companies()->attach($company);
                            }

                            // Attach selected contacts
                            $contacts = \App\Models\ContactDetail::whereIn('id', $contactIds)->get();
                            foreach ($contacts as $contact) {
                                $patch->contacts()->attach($contact);
                            }
                        }
                    })
                    ->afterStateHydrated(function ($set, $state, $get, $livewire) {
                        $record = $livewire->getRecord();
                        if (! $record) return;
                    
                        $selected = [];
                    
                        foreach ($record->companies as $company) {
                            $selected[] = "company_{$company->id}";
                        }
                    
                        foreach ($record->contacts as $contact) {
                            $selected[] = "contact_{$contact->id}";
                        }
                    
                        $set('patchables', $selected);
                    })
                    ->required()
                    ->columnSpanFull(),

                TextInput::make('created_by')
                    ->default(Auth::user()->name ?? 'System') // Fallback if user is somehow not available
                    ->disabled()
                    ->dehydrated(false)
                    ->label('Created By'),

                TextInput::make('updated_by')
                    ->default(Auth::user()->name ?? 'System')
                    ->disabled()
                    ->dehydrated(false)
                    ->label('Last Updated By'),
            ])->columns(2); // Set a default number of columns for the form
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('code')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('territory.name')
                    ->label('Territory')
                    ->sortable()
                    ->searchable(), // Allow searching by territory name

                TextColumn::make('cityPinCodes.pin_code') // Corrected relationship name if it's many-to-many
                    ->label('Associated Pin Codes') // More accurate label
                    ->badge() // Display as badges for multiple pin codes
                    ->separator(',') // Separate multiple pin codes with a comma
                    ->sortable()
                    ->searchable(),

                ColorColumn::make('color')
                    ->label('Patch Color'),

                TextColumn::make('description')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->description)
                    ->toggleable(isToggledHiddenByDefault: true), // Hide by default for cleaner table

                TextColumn::make('patchables_summary') // Custom column to summarize assigned entities
                    ->label('Assigned Entities')
                    ->getStateUsing(function ($record) {
                        $entities = $record->patchables->map(function ($patchable) {
                            return $patchable instanceof Company
                                ? "Company: {$patchable->name}"
                                : "Contact: {$patchable->full_name} ({$patchable->email})"; // Using full_name as per Contact model
                        });
                        return $entities->implode(', ');
                    })
                    ->limit(70) // Limit displayed text
                    ->tooltip(fn ($record) => $record->patchables->map(function ($patchable) {
                        return $patchable instanceof Company
                            ? "Company: {$patchable->name}"
                            : "Contact: {$patchable->full_name} ({$patchable->email})";
                    })->implode("\n")) // Show full list on tooltip with line breaks
                    ->listWithLineBreaks()
                    ->bulleted(),

                TextColumn::make('created_by')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // Hide by default

                TextColumn::make('updated_by')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // Hide by default

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // You can add more filters here, e.g., for territory, creation date, etc.
                // Tables\Filters\SelectFilter::make('territory_id')
                //     ->relationship('territory', 'name')
                //     ->label('Filter by Territory'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (Tables\Actions\DeleteAction $action, $record) {
                        // Ensure 'deleted_by' is set before deletion
                        $record->update(['deleted_by' => Auth::user()->name ?? 'System']);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->before(function (Tables\Actions\DeleteBulkAction $action, $records) {
                        // Set 'deleted_by' for all records before bulk deletion
                        $records->each(fn ($record) => $record->update(['deleted_by' => Auth::user()->name ?? 'System']));
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Consider adding relation managers here if you have related models that need to be managed directly from the Patch resource.
            // Example: PatchablesRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPatches::route('/'),
            'create' => Pages\CreatePatch::route('/create'),
            'edit' => Pages\EditPatch::route('/{record}/edit'),
        ];
    }

    /**
     * Define the default ordering for the table.
     */
    protected static ?string $defaultSortColumn = 'name';
    protected static ?string $defaultSortDirection = 'asc';
}