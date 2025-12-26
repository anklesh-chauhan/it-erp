<?php

namespace App\Filament\Resources\Patches;

use App\Traits\HasSafeGlobalSearch;

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
use Filament\Actions\ActionGroup;
use Filament\Tables\Enums\RecordActionsPosition;
use App\Filament\Actions\ApprovalAction;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Facades\Filament;

class PatchResource extends Resource
{
    use HasSafeGlobalSearch;

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
                    ->label('Territory')
                    ->relationship(
                        name: 'territory',
                        titleAttribute: 'name',
                        modifyQueryUsing: function (Builder $query) {

                            $user = Auth::user();

                            // SAFETY CHECKS
                            if (
                                ! $user?->employee?->employmentDetail
                            ) {
                                // No employment detail → no territory
                                $query->whereRaw('1 = 0');
                                return;
                            }

                            // ✅ Get ALL division OU IDs
                            $divisionOuIds = $user->employee
                                ->employmentDetail
                                ->organizationalUnits()
                                ->pluck('organizational_units.id')
                                ->toArray();

                            if (empty($divisionOuIds)) {
                                // No divisions → no territory
                                $query->whereRaw('1 = 0');
                                return;
                            }

                            // ✅ Filter territories by linked divisions
                            $query->whereHas('divisions', function ($q) use ($divisionOuIds) {
                                $q->whereIn('organizational_units.id', $divisionOuIds);
                            });
                        }
                    )
                    ->preload()
                    ->searchable()
                    ->required()
                    ->live()
                    ->placeholder('Select a Territory')
                    ->columnSpan(1),

                Select::make('city_pin_code_id')
                    ->label('City Pin Code')
                    ->placeholder('Select a City Pin Code')
                    ->relationship(
                        name: 'cityPinCode',
                        titleAttribute: 'pin_code',
                        modifyQueryUsing: function (Builder $query, Get $get) {
                            $territoryId = $get('territory_id');

                            if (! $territoryId) {
                                // No territory → no pin codes
                                $query->whereRaw('1 = 0');
                                return;
                            }

                            $query->whereHas('territories', function ($q) use ($territoryId) {
                                $q->where('territories.id', $territoryId);
                            });
                        }
                    )
                    ->searchable()
                    ->preload()
                    ->nullable()
                    ->reactive()
                    ->columnSpan(1),

                ColorPicker::make('color')
                            ->nullable()
                            ->label('Patch Color'),

                Textarea::make('description')
                    ->label('Remarks')
                    ->nullable()
                    ->maxLength(65535)
                    ->rows(2),



                        Hidden::make('created_by')
                            ->default(Auth::user()->name ?? 'System') // Fallback if user is somehow not available
                            ->disabled()
                            ->dehydrated(false)
                            ->label('Created By'),

                        Hidden::make('updated_by')
                            ->default(Auth::user()->name ?? 'System')
                            ->disabled()
                            ->dehydrated(false)
                            ->label('Last Updated By'),


            ])->columns(2); // Set a default number of columns for the form
    }

    public static function table(Table $table): Table
    {
        $table = parent::table($table);
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
                ActionGroup::make([
                    ApprovalAction::make(),
                    EditAction::make(),
                    DeleteAction::make()
                        ->before(function (DeleteAction $action, $record) {
                            // Ensure 'deleted_by' is set before deletion
                            $record->update(['deleted_by' => Auth::user()->name ?? 'System']);
                        }),
                ]),
            ], position: RecordActionsPosition::BeforeColumns)
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
            RelationManagers\CompaniesRelationManager::class,
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
