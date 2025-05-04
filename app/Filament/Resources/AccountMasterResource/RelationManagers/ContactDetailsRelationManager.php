<?php

namespace App\Filament\Resources\AccountMasterResource\RelationManagers;

use App\Traits\CreateContactFormTrait;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ContactDetailsRelationManager extends RelationManager
{
    use CreateContactFormTrait;

    protected static string $relationship = 'contactDetails';

    protected $listeners = ['close-attached-modal' => 'closeAttachModal'];

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                ...self::getCreateContactFormTraitFields(),
            ]);
    }

    public function closeAttachModal()
    {
        $this->dispatch('closeModal');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('full_name')
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('mobile_number')
                    ->label('Phone')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('company.name')
                    ->label('Company')
                    ->sortable()
                    ->searchable()
                    ->default('No Company'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('company')
                    ->relationship('company', 'name')
                    ->preload(),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->label('Attach Contact')
                    ->closeModalByClickingAway(false)
                    ->form(function (AttachAction $action): array {
                        return [
                            Forms\Components\Select::make('recordId')
                                ->label('Contact')
                                ->options(function () {
                                    return \App\Models\ContactDetail::query()
                                        ->get()
                                        ->mapWithKeys(fn ($contact) => [
                                            $contact->id => "{$contact->full_name} — " . ($contact->company?->name ?? 'No Company')
                                        ])
                                        ->toArray();
                                })
                                ->searchable()
                                ->getSearchResultsUsing(function (string $search) {
                                    return \App\Models\ContactDetail::query()
                                        ->where(function ($query) use ($search) {
                                            $query->where('first_name', 'like', "%{$search}%")
                                                ->orWhere('last_name', 'like', "%{$search}%")
                                                ->orWhereHas('company', fn ($query) =>
                                                    $query->where('name', 'like', "%{$search}%")
                                                );
                                        })
                                        ->get()
                                        ->mapWithKeys(fn ($contact) => [
                                            $contact->id => "{$contact->full_name} — " . ($contact->company?->name ?? 'No Company')
                                        ]);
                                })
                                ->getOptionLabelUsing(fn ($value) =>
                                    ($contact = \App\Models\ContactDetail::find($value))
                                        ? "{$contact->full_name} — " . ($contact->company?->name ?? 'No Company')
                                        : 'Unknown Contact'
                                )
                                ->preload()
                                ->live()
                                ->helperText('Search for a contact. If not found, use the "Create New Contact" action below.')
                                ->required(),
                            Forms\Components\Placeholder::make('create_info')
                                ->content('Can’t find the contact? Create a new one below.'),
                        ];
                    })
                    ->action(function (array $data, RelationManager $livewire) {
                        $contactId = $data['recordId'];
                        $livewire->ownerRecord->contactDetails()->attach($contactId);
                        Notification::make()
                            ->title('Contact Attached')
                            ->success()
                            ->send();
                    })
                    ->extraModalFooterActions(function (AttachAction $action) {
                        return [
                            CreateAction::make()
                                ->label('Create New Contact')
                                ->icon('heroicon-o-plus')
                                ->modalWidth('4xl')
                                ->form(self::getCreateContactFormTraitFields())
                                ->createAnother(false)
                                ->mutateFormDataUsing(function (array $data) {
                                    return $data; // Optional: Transform data if needed
                                })
                                ->action(function (array $data, RelationManager $livewire) {
                                    // Create the contact manually
                                    $contact = \App\Models\ContactDetail::create($data);

                                    // Attach it to the current AccountMaster
                                    $livewire->ownerRecord->contactDetails()->attach($contact->id);

                                    // Attach to company if exists
                                    $company = \App\Models\Company::where('account_master_id', $livewire->ownerRecord->id)->first();
                                    if ($company) {
                                        $contact->company_id = $company->id;
                                        $contact->save();
                                    }

                                    Notification::make()
                                        ->title('Contact Created and Attached')
                                        ->success()
                                        ->send();

                                    // Close modal manually
                                    $livewire->dispatch('close-modal'); // Close inner "Create Contact"
                                    $livewire->dispatch('close-attached-modal'); // Custom event to close parent
                                }),
                        ];
                    }),

                Tables\Actions\CreateAction::make()
                    ->after(function (RelationManager $livewire, $record) {
                        $accountMaster = $livewire->getOwnerRecord();
                        $company = \App\Models\Company::where('account_master_id', $accountMaster->id)->first();

                        if ($company) {
                            $record->company_id = $company->id;
                            $record->save();

                            \Filament\Notifications\Notification::make()
                                ->title('Contact linked to Company')
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
