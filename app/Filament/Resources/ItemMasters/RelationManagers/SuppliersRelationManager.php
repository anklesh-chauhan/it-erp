<?php

namespace App\Filament\Resources\ItemMasterResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\CreateAction;
use App\Traits\CreateAccountMasterTrait;
use Filament\Notifications\Notification;

class SuppliersRelationManager extends RelationManager
{
    use CreateAccountMasterTrait;

    protected static string $relationship = 'suppliers';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                ...self::getCreateAccountMasterTraitFields(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->label('Attach Supplier')
                    ->closeModalByClickingAway(false)
                    ->form(function (AttachAction $action): array {
                        return [
                            Forms\Components\Select::make('recordId')
                                ->label('Supplier')
                                ->options(function () {
                                    return \App\Models\AccountMaster::query()
                                        ->get()
                                        ->mapWithKeys(fn ($account_master) => [
                                            $account_master->id => "{$account_master->name} — " . ($account_master->account_code ?? 'No Account Code')
                                        ])
                                        ->toArray();
                                })
                                ->searchable()
                                ->getSearchResultsUsing(function (string $search) {
                                    return \App\Models\AccountMaster::query()
                                        ->where(function ($query) use ($search) {
                                            $query->where('name', 'like', "%{$search}%")
                                                ->orWhere('account_code', 'like', "%{$search}%")
                                                ->orWhere('email', 'like', "%{$search}%")
                                                ->orWhere('phone_number', 'like', "%{$search}%");
                                        })
                                        ->get()
                                        ->mapWithKeys(fn ($account_master) => [
                                            $account_master->id => "{$account_master->name} — " . ($account_master->account_code ?? 'No Account Code')
                                        ]);
                                })
                                ->getOptionLabelUsing(fn ($value) =>
                                    ($account_master = \App\Models\AccountMaster::find($value))
                                        ? "{$account_master->name} — " . ($account_master->account_code ?? 'No Account Code')
                                        : 'Unknown Supplier'
                                )
                                ->preload()
                                ->live()
                                ->helperText('Search for a Supplier. If not found, use the "Create New Supplier" action below.')
                                ->required(),
                            Forms\Components\Placeholder::make('create_info')
                                ->content('Can’t find the contact? Create a new one below.'),
                        ];
                    })
                    ->action(function (array $data, RelationManager $livewire) {
                        $suppliersID = $data['recordId'];
                        // Prevent duplicate attachments
                        if (!$livewire->ownerRecord->suppliers()->where('account_masters.id', $suppliersID)->exists()) {
                            $livewire->ownerRecord->suppliers()->attach($suppliersID);
                            Notification::make()
                                ->title('Supplier Attached')
                                ->success()
                                ->send();
                        }
                    })
                    ->extraModalFooterActions(function (AttachAction $action) {
                        return [
                            CreateAction::make()
                                ->label('Create Supplier')
                                ->icon('heroicon-o-plus')
                                ->modalWidth('4xl')
                                ->form(self::getCreateAccountMasterTraitFields())
                                ->createAnother(false)
                                ->after(function (RelationManager $livewire, $record) {
                                    // Prevent duplicate attachments
                                    if (!$livewire->ownerRecord->suppliers()->where('account_masters.id', $record->id)->exists()) {
                                        $livewire->ownerRecord->suppliers()->attach($record->id);
                                        Notification::make()
                                            ->title('Supplier Created and Attached')
                                            ->success()
                                            ->send();
                                    }

                                    // Close the modal and reset the form to prevent AttachAction submission
                                    $livewire->dispatch('close-modal');
                                }),
                        ];
                    }),
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
