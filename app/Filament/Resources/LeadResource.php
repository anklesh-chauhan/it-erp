<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeadResource\Pages;
use App\Filament\Resources\LeadResource\RelationManagers;
use App\Models\Lead;
use App\Models\CityPinCode;
use App\Models\LeadCustomField;
use App\Models\NumberSeries;
use App\Models\ItemMaster;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Tables\Columns\SelectColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Facades\Filament;
use Filament\GlobalSearch\GlobalSearchResult;
use Illuminate\Support\Collection;
use App\Traits\HasCustomerInteractionFields;



class LeadResource extends Resource
{
    use HasCustomerInteractionFields;
    use \App\Traits\AddressDetailsTrait;
    use \App\Traits\HasSafeGlobalSearch;

    protected static ?string $model = \App\Models\Lead::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Marketing';
    protected static ?int $navigationSort = 10;

    /**
     * @var \App\Models\LeadStatus
     */

    protected static ?string $statusModel = \App\Models\LeadStatus::class;

    protected static ?string $recordTitleAttribute = 'reference_code';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                ...self::getCommonFormSchema(),

                // ✅ Address Details
                Forms\Components\Section::make('Other Details')
                ->collapsible()
                ->schema([

                    ...self::getAddressDetailsTraitField(),

                    Forms\Components\Grid::make(4)
                        ->schema([
                            Forms\Components\Select::make('lead_source_id')
                                ->relationship('leadSource', 'name')
                                ->required(),

                            Forms\Components\Select::make('rating_type_id')
                                ->relationship('rating', 'name')
                                ->preload(),

                            Forms\Components\TextInput::make('annual_revenue')
                                ->numeric()
                                ->prefix('Rs')
                                ->label('Annual Revenue'),

                            Forms\Components\Textarea::make('description')
                                ->nullable()
                                ->label('Description Information'),
                        ]),


                    // Dynamic Custom Fields
                    Forms\Components\Group::make([
                        Forms\Components\Section::make('More Details')
                            ->hidden(fn () => \App\Models\LeadCustomField::count() === 0)
                            ->schema(function () {
                                return \App\Models\LeadCustomField::all()->map(function ($field) {
                                    return match ($field->type) {
                                        'text' => Forms\Components\TextInput::make("custom_fields.{$field->name}")
                                            ->label($field->label)
                                            ->required(),
                                        'number' => Forms\Components\TextInput::make("custom_fields.{$field->name}")
                                            ->numeric()
                                            ->label($field->label)
                                            ->required(),
                                        'date' => Forms\Components\DatePicker::make("custom_fields.{$field->name}")
                                            ->label($field->label)
                                            ->required(),
                                        'email' => Forms\Components\TextInput::make("custom_fields.{$field->name}")
                                            ->email()
                                            ->label($field->label)
                                            ->required(),
                                        default => null,
                                    };
                                })->toArray();
                            })
                            ->columns(2)
                        ]),

                ]),

    ]);

    }


    public static function mutateFormDataBeforeCreate(array $data): array
    {

        $data['custom_fields'] = collect($data['custom_fields'])
            ->filter(fn ($value) => !is_null($value)) // Filter out empty fields
            ->toArray();

        return $data;
    }

    public static function mutateFormDataBeforeSave(array $data): array
    {

        $data['custom_fields'] = collect($data['custom_fields'])
            ->filter(fn ($value) => !is_null($value)) // Filter out empty fields
            ->toArray();

        return $data;
    }


    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('owner.name')
                    ->label('Owner')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('contactDetail.full_name')
                    ->label('Contact Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('company.name')
                    ->label('Company')
                    ->searchable()
                    ->sortable(),

                SelectColumn::make('status_id')
                    ->label('Status')
                    ->options(
                        \App\Models\LeadStatus::pluck('name', 'id')
                    )
                    ->getStateUsing(fn ($record) => $record->status_id)
                    ->afterStateUpdated(function ($record, $state) {
                        $record->update([
                            'status_id' => $state,
                            'status_type' => \App\Models\LeadStatus::class, // ✅ Ensure status_type is set
                        ]);

                        Notification::make()
                            ->title('Lead status updated successfully!')
                            ->success()
                            ->send();
                    }),

                Tables\Columns\TextColumn::make('followups.next_follow_up_date')
                    ->label('Next Follow-up Date')
                    ->formatStateUsing(function ($record) {
                        $nextFollowUp = $record->followups()->orderByDesc('next_follow_up_date')->first();
                        if (!$nextFollowUp || !$nextFollowUp->next_follow_up_date) {

                            return '<a href="' . route('filament.admin.resources.follow-ups.create', ['lead_id' => $record->id]) . '" class="text-blue-500 underline">Add Follow-up</a>';
                        }
                        return $nextFollowUp->next_follow_up_date;
                    })
                    ->html(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Dynamically add custom fields
                ...LeadCustomField::all()->map(function ($field) {
                    return Tables\Columns\TextColumn::make("custom_fields.{$field->name}")
                        ->label($field->label)
                        ->sortable()
                        ->searchable()
                        ->toggleable(isToggledHiddenByDefault: true);
                })->toArray()

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    TableAction::make('convert_to_deal')
                        ->label('Convert to Deal')
                        ->icon('heroicon-o-arrow-right-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->form([
                            Forms\Components\Checkbox::make('create_account_master')
                                ->label('Create Account Master')
                                ->default(false)
                                ->visible(function ($get, $record) {
                                    return $record->company?->account_master_id === null;
                                }),
                        ])
                        ->action(function ($record, array $data) {
                            $createAccountMaster = $data['create_account_master'] ?? false;
                            // Convert the lead to a deal and optionally create a Company Master
                            $deal = $record->convertToDeal(createAccountMaster: $createAccountMaster);

                            Notification::make()
                                ->title('Lead Converted')
                                ->body("Lead {$record->reference_code} has been converted to Deal {$deal->reference_code}.")
                                ->success()
                                ->send();
                        })
                        ->visible(fn ($record) => $record->status?->name !== 'Converted'),
                    TableAction::make('addFollowUp')
                        ->label('Add Follow-up')
                        ->icon('heroicon-o-plus')
                        ->form([
                            Forms\Components\Hidden::make('user_id')
                                ->default(Auth::id()) // Automatically sets the current logged-in user
                                ->required(),

                            Forms\Components\Hidden::make('lead_id')
                                ->default(fn ($record) => $record->id),

                            Forms\Components\DateTimePicker::make('follow_up_date')
                                    ->required()
                                    ->label('Follow-up Date'),

                                    Forms\Components\Select::make('to_whom')
                                    ->options(function (callable $get) {
                                        $lead = \App\Models\Lead::find($get('lead_id'));

                                        if ($lead) {
                                            return \App\Models\ContactDetail::where('company_id', $lead->company_id)
                                                ->get()
                                                ->mapWithKeys(fn ($contact) => [
                                                    $contact->id => "{$contact->first_name} {$contact->last_name}"
                                                ]);
                                        }
                                        return [];
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->label('To Whom'),

                            Forms\Components\Textarea::make('interaction')
                                ->label('Interaction')
                                ->rows(3)
                                ->nullable(),

                            Forms\Components\Textarea::make('outcome')
                                ->label('Outcome')
                                ->rows(2)
                                ->nullable(),

                            Forms\Components\Select::make('follow_up_media_id')
                                ->options(\App\Models\FollowUpMedia::pluck('name', 'id')->toArray())
                                ->label('Media')
                                ->nullable(),

                            Forms\Components\DateTimePicker::make('next_follow_up_date')
                                ->label('Next Follow-up Date')
                                ->nullable(),
                        ])
                        ->action(function ($record, array $data) {
                            $record->followUps()->create($data);
                            Notification::make()
                                ->title('Follow-up added successfully!')
                                ->success()
                                ->send();
                        })
                        ->modalHeading('Add New Follow-up')
                        ->modalSubmitActionLabel('Save Follow-up')
                        ->requiresConfirmation('Are you sure you want to add this follow-up?')
                        ->modalWidth('2xl'),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }


    public static function getRelations(): array
    {
        return [
            RelationManagers\LeadFollowUpRelationManager::class,
            RelationManagers\ItemMastersRelationManager::class,
            RelationManagers\LeadNotesRelationManager::class,
            RelationManagers\LeadActivityRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeads::route('/'),
            'create' => Pages\CreateLead::route('/create'),
            'edit' => Pages\EditLead::route('/{record}/edit'),
            'custom-fields' => Pages\CustomFields::route('/custom-fields'),
        ];
    }
}
