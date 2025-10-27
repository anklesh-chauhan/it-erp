<?php

namespace App\Filament\Resources\Patches;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\Patches\Pages\ListPatches;
use App\Filament\Resources\Patches\Pages\CreatePatch;
use App\Filament\Resources\Patches\Pages\EditPatch;
use App\Filament\Resources\PatchResource\Pages;
use App\Models\AccountMaster;
use App\Models\Patch;
use App\Models\CityPinCode;
use App\Models\ContactDetail; // Renamed for clarity in this context
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ColorPicker;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\Hidden;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Group;
use App\Models\Territory;

class PatchResource extends Resource
{
    protected static ?string $model = Patch::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static string | \UnitEnum | null $navigationGroup = 'Marketing';
    // Added a label for better readability in the navigation
    protected static ?string $navigationLabel = 'Patches';

    // Optional: Add a slug for cleaner URLs if needed
    // protected static ?string $slug = 'patches';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
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

                // Add All from Territory button
                Group::make()
                    ->schema([
                        Action::make('addAllFromTerritory')
                            ->label('Add All from Territory')
                            ->icon('heroicon-o-plus-circle')
                            ->color('primary')
                            ->visible(fn (Get $get) => filled($get('territory_id')))
                            ->action(function ($livewire, $get, $set) {
                                $territoryId = $get('territory_id');

                                if (! $territoryId) {
                                    Notification::make()
                                        ->title('Select Territory')
                                        ->body('Please select a territory before adding related companies and contacts.')
                                        ->danger()
                                        ->send();
                                    return;
                                }

                                // Get all city_pin_code_ids linked to this territory
                                $territory = Territory::with('cityPinCodes')->find($territoryId);
                                $pinCodeIds = $territory->cityPinCodes()->pluck('pin_code')->toArray();

                                if (empty($pinCodeIds)) {
                                    Notification::make()
                                        ->title('No Pin Codes Found')
                                        ->body('This territory has no linked city pin codes.')
                                        ->warning()
                                        ->send();
                                    return;
                                }

                                // 🏢 Companies linked via addresses
                                $companies = AccountMaster::whereHas('addresses', function ($query) use ($pinCodeIds) {
                                    $query->whereIn('pin_code', $pinCodeIds);
                                })->get();

                                // 👤 Contacts linked via addresses
                                $contacts = ContactDetail::whereHas('addresses', function ($query) use ($pinCodeIds) {
                                    $query->whereIn('pin_code', $pinCodeIds);
                                })->get();

                                $newEntries = [];
                                $order = 1;

                                foreach ($companies as $company) {
                                    $newEntries[] = [
                                        'patchable_type' => AccountMaster::class,
                                        'patchable_id'   => $company->id,
                                        'order'          => $order++,
                                    ];
                                }

                                foreach ($contacts as $contact) {
                                    $newEntries[] = [
                                        'patchable_type' => ContactDetail::class,
                                        'patchable_id'   => $contact->id,
                                        'order'          => $order++,
                                    ];
                                }

                                // Merge without duplicates
                                $existing = $get('patchables') ?? [];

                                // 🧹 Remove the default empty row Filament adds
                                $existing = array_filter($existing, function ($row) {
                                    return !empty($row['patchable_type']) && !empty($row['patchable_id']);
                                });

                                $existingKeys = collect($existing)
                                    ->map(fn ($e) => $e['patchable_type'].'-'.$e['patchable_id'])
                                    ->toArray();

                                $filtered = collect($newEntries)
                                    ->reject(fn ($entry) => in_array($entry['patchable_type'].'-'.$entry['patchable_id'], $existingKeys))
                                    ->values()
                                    ->all();

                                $set('patchables', array_merge($existing, $filtered));

                                // CREATE MODE: no record yet — we must inject into form state and then call the create method
                                // Clean existing repeater state (remove blank rows)
                                $existingState = $get('patchables') ?? [];
                                $existingState = array_values(array_filter($existingState, fn($r) => !empty($r['patchable_type']) && !empty($r['patchable_id'])));

                                // Merge unique new entries
                                $existingKeys = collect($existingState)->map(fn($e) => $e['patchable_type'].'-'.$e['patchable_id'])->toArray();
                                $toAdd = [];
                                foreach ($newEntries as $entry) {
                                    $key = $entry['patchable_type'].'-'.$entry['patchable_id'];
                                    if (! in_array($key, $existingKeys)) {
                                        $toAdd[] = $entry;
                                    }
                                }

                                // Set repeater state so form will include these on submit
                                $set('patchables', array_merge($existingState, $toAdd));

                                // Now attempt to call the page create/store method (best-effort)
                                if (method_exists($livewire, 'create')) {
                                    $livewire->create();
                                    Notification::make()->title('Created')->success()->send();
                                    return;
                                }

                                if (method_exists($livewire, 'createRecord')) {
                                    $livewire->createRecord();
                                    Notification::make()->title('Created')->success()->send();
                                    return;
                                }

                                if (method_exists($livewire, 'store')) {
                                    $livewire->store();
                                    Notification::make()->title('Saved')->success()->send();
                                    return;
                                }

                                Notification::make()
                                    ->title('Added All Related Records')
                                    ->body('All Companies and Contacts from this Territory have been added.')
                                    ->success()
                                    ->send();

                            }),
                    ])->columnSpanFull(),

                // Table-style repeater bound to the patchables() hasMany relationship
                Repeater::make('patchables')
                    ->label('Assigned Companies or Contacts')
                    ->relationship('patchables')   // binds to Patch::patchables()
                    ->orderColumn('order')         // Filament will persist the 'order' column
                    ->reorderable(true)
                    ->table([
                        TableColumn::make('Type'),
                        TableColumn::make('Company/Contact Name'),
                    ])
                    ->schema([
                        // CRITICAL: the primary key name MUST be `id` so Filament can map rows to DB records
                        Hidden::make('id'),

                        // store model class (AccountMaster::class or ContactDetail::class)
                        Select::make('patchable_type')
                            ->label('Type')
                            ->options([
                                AccountMaster::class => 'Company',
                                ContactDetail::class => 'Contact',
                            ])
                            ->default(AccountMaster::class)
                            ->reactive()
                            ->required()
                            ->afterStateUpdated(fn ($state, $set) => $set('patchable_id', null)),

                        // store the related model id
                        Select::make('patchable_id')
            ->label('Name')
            ->options(function (Get $get) {
                $territoryId = $get('../../territory_id'); // 👈 get selected Territory ID
                $type = $get('patchable_type');

                if (! $territoryId) {
                    return []; // no territory selected
                }

                // Get all pin codes linked to this territory
                $territory = \App\Models\Territory::with('cityPinCodes')->find($territoryId);
                $pinCodes = $territory?->cityPinCodes?->pluck('pin_code') ?? collect();

                if ($pinCodes->isEmpty()) {
                    return [];
                }

                // Filter based on patchable type
                if ($type === \App\Models\AccountMaster::class) {
                    return \App\Models\AccountMaster::whereHas('addresses', function ($q) use ($pinCodes) {
                            $q->whereIn('pin_code', $pinCodes);
                        })
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->toArray();
                }

                if ($type === \App\Models\ContactDetail::class) {
                    return \App\Models\ContactDetail::whereHas('addresses', function ($q) use ($pinCodes) {
                            $q->whereIn('pin_code', $pinCodes);
                        })
                        ->orderBy('first_name')
                        ->get()
                        ->mapWithKeys(fn ($c) => [
                            $c->id => "{$c->first_name} {$c->last_name} ({$c->email})",
                        ])
                        ->toArray();
                }

                return [];
            })
            ->reactive()
            ->searchable()
            ->required(),

                        // keep order visible but Filament manages persisting it
                        Hidden::make('order'), // use hidden so user doesn't edit it manually
                    ])
                    // hydrate only if repeater state is empty (prevents overwriting on reorder)
                    ->afterStateHydrated(function (callable $set, $state, $record) {
                        if (! $record) {
                            return;
                        }

                        // If state already populated (user interaction), don't overwrite
                        if (! empty($state) && count($state) > 0) {
                            return;
                        }

                        $rows = $record->patchables()
                            ->orderBy('order')
                            ->get()
                            ->map(fn ($p) => [
                                'id' => $p->id,
                                'patchable_type' => $p->patchable_type,
                                'patchable_id' => $p->patchable_id,
                                'order' => $p->order,
                            ])
                            ->toArray();

                        $set('patchables', $rows);
                    })
                    ->columnSpanFull(),
                    //End of patchables field

                ColorPicker::make('color')
                    ->nullable()
                    ->label('Patch Color')
                    ->helperText('Choose a color to visually represent the patch.')
                    ->columnSpanFull(),

                Textarea::make('description')
                    ->nullable()
                    ->maxLength(65535)
                    ->columnSpanFull()
                    ->rows(3) // Provide a reasonable default height
                    ->helperText('A brief description of the patch.'),

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
                    // Explicitly retrieve the name state to ensure clean display
                    ->getStateUsing(fn ($record) => $record->territory?->name)
                    // Custom search on the relationship
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('territory', fn (Builder $subQuery) => $subQuery->where('name', 'like', "%{$search}%"));
                    })
                    // Custom sort on the relationship
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        // Order by territory name
                        return $query->join('territories', 'patches.territory_id', '=', 'territories.id')
                            ->orderBy('territories.name', $direction)
                            ->select('patches.*'); // Select patches.* to avoid conflicts with territory columns
                    }),

                ColorColumn::make('color')
                    ->label('Patch Color'),

                TextColumn::make('patchables_summary')
                    ->label('Assigned Entities')
                    ->getStateUsing(function ($record) {
                        return $record->patchables
                            ->map(function ($patchable) {
                                $entity = $patchable->patchable; // morphTo relation
                                if (! $entity) {
                                    return null;
                                }

                                if ($patchable->patchable_type === \App\Models\AccountMaster::class) {
                                    return "🏢 {$entity->name}";
                                }

                                if ($patchable->patchable_type === \App\Models\ContactDetail::class) {
                                    // Assuming ContactDetail has first_name, last_name, and email fields
                                    $fullName = trim("{$entity->first_name} {$entity->last_name}");
                                    return "👤 {$fullName}";
                                }

                                return null;
                            })
                            ->filter()
                            ->implode(', ');
                    })
                    ->limit(70) // Limit displayed text
                    ->tooltip(function ($record) {
                        if (! $record->patchables) {
                            return null;
                        }

                        return $record->patchables
                            ->map(function ($patchable) {
                                $entity = $patchable->patchable; // morphTo relation
                                if (! $entity) {
                                    return null;
                                }

                                if ($patchable->patchable_type === \App\Models\AccountMaster::class) {
                                    return "🏢 {$entity->name}";
                                }

                                if ($patchable->patchable_type === \App\Models\ContactDetail::class) {
                                    $fullName = trim("{$entity->first_name} {$entity->last_name}");
                                    return "👤 {$fullName}";
                                }

                                return null;
                            })
                            ->filter()
                            ->implode("\n"); // new line for each item in tooltip
                    })
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
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->before(function (DeleteAction $action, $record) {
                        // Ensure 'deleted_by' is set before deletion
                        $record->update(['deleted_by' => Auth::user()->name ?? 'System']);
                    }),
            ])
            ->toolbarActions([
                DeleteBulkAction::make()
                    ->before(function (DeleteBulkAction $action, $records) {
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
            'index' => ListPatches::route('/'),
            'create' => CreatePatch::route('/create'),
            'edit' => EditPatch::route('/{record}/edit'),
        ];
    }

    /**
     * Define the default ordering for the table.
     */
    protected static ?string $defaultSortColumn = 'name';
    protected static ?string $defaultSortDirection = 'asc';
}
