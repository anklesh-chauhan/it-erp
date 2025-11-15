<?php

namespace App\Filament\Resources\Territories\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Models\CityPinCode;
use App\Models\Patch;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Auth;

class PatchesRelationManager extends RelationManager
{
    protected static string $relationship = 'patches';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                TextInput::make('code')
                    ->maxLength(100)
                    ->unique(ignoreRecord: true),

                Select::make('city_pin_code_id')
                    ->label('City / Pin Code')
                    ->options(
                        CityPinCode::with('city')
                            ->get()
                            ->mapWithKeys(fn ($item) => [
                                $item->id => "{$item->city->name} - {$item->area_town} ({$item->pin_code})"
                            ])
                            ->toArray()
                    )
                    ->searchable(),

                ColorPicker::make('color')
                    ->label('Color Tag')
                    ->nullable(),

                Textarea::make('description')
                    ->label('Description')
                    ->rows(2)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('patches')
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('code')->sortable(),
                TextColumn::make('cityPinCode.pin_code')->label('Pin Code'),
                TextColumn::make('color')->badge()->color(fn ($state) => $state ?? 'gray'),
                TextColumn::make('created_by')->label('Created By'),
                TextColumn::make('updated_by')->label('Updated By'),
                TextColumn::make('created_at')->dateTime()->since()->label('Created'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                // EditAction::make(),
            ])
            ->toolbarActions([
                //
            ]);
    }
}
