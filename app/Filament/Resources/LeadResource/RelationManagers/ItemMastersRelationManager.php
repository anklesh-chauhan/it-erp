<?php

namespace App\Filament\Resources\LeadResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\ItemMaster;
use App\Models\LeadActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemMastersRelationManager extends RelationManager
{
    protected static string $relationship = 'itemMasters';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('recordId') // Change to Select
                    ->label('Item Name')
                    ->options(ItemMaster::query()->pluck('item_name', 'id')) // Load Item Names
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('quantity')
                    ->numeric()
                    ->required()
                    ->default(1),

                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('item_name')
            ->columns([
                Tables\Columns\TextColumn::make('item_name')->label('Item Name'),
                Tables\Columns\TextColumn::make('pivot.quantity')->label('Quantity'),
                Tables\Columns\TextColumn::make('pivot.price')->label('Price'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->recordSelect(function () {
                        return ItemMaster::query()->pluck('item_name', 'id'); // Load item names
                    })
                    ->preloadRecordSelect()
                    ->form([

                        Forms\Components\Select::make('recordId')
                        ->label('Item Name')
                        ->options(ItemMaster::query()->pluck('item_name', 'id')) // Load Item Names
                        ->searchable()
                        ->required(),

                        Forms\Components\TextInput::make('quantity')
                            ->numeric()
                            ->minValue(1)
                            ->required(),
                        Forms\Components\TextInput::make('price')
                            ->numeric()
                            ->prefix('$')
                            ->minValue(0)
                            ->required(),
                    ])

                ->action(function (array $data, RelationManager $livewire) {
                    $record = $livewire->getOwnerRecord(); // Get the parent record

                    $record->itemMasters()->attach($data['recordId'], [
                        'quantity' => $data['quantity'],
                        'price' => $data['price'],
                    ]);
                })
                ->after(function (RelationManager $livewire, array $data) {
                    $lead = $livewire->getOwnerRecord(); // Get the lead (parent record)
                    $item = ItemMaster::find($data['recordId']); // Get the attached item

                    if ($lead && $item) {
                        LeadActivity::create([
                            'lead_id' => $lead->id,
                            'user_id' => Auth::id(),
                            'activity_type' => 'Item Attached',
                            'description' => "Item '{$item->item_name}' has been linked to the lead.",
                        ]);
                    }
                }),
            ])
            ->actions([
                Tables\Actions\DetachAction::make()
                ->action(function ($record, RelationManager $livewire) {
                    $livewire->ownerRecord->itemMasters()->detach($record->id);
                })
                ->after(function (RelationManager $livewire, $record) {
                    $lead = $livewire->getOwnerRecord();
                    $item = $record; // The $record is the ItemMaster that was just detached

                    if ($lead && $item) {
                        LeadActivity::create([
                            'lead_id' => $lead->id,
                            'user_id' => Auth::id(),
                            'activity_type' => 'Item Detached',
                            'description' => "Item '{$item->item_name}' has been removed from the lead.",
                        ]);
                    }
                }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make()
                        ->action(function ($records, RelationManager $livewire) {
                            $lead = $livewire->getOwnerRecord();
                            $itemIds = $records->pluck('id')->toArray();
                            $livewire->ownerRecord->itemMasters()->detach($itemIds);

                            foreach ($records as $item) {
                                LeadActivity::create([
                                    'lead_id' => $lead->id,
                                    'user_id' => Auth::id(),
                                    'activity_type' => 'Item Detached',
                                    'description' => "Item '{$item->item_name}' has been removed from the lead.",
                                ]);
                            }
                        }),
                ]),
            ]);
    }
}
