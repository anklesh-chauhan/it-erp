<?php

namespace App\Filament\Resources\LeadResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\LeadResource;
use Filament\Forms;

class ViewLead extends ViewRecord
{
    protected static string $resource = LeadResource::class;

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('first_name')
                ->label('First Name'),

            Forms\Components\TextInput::make('last_name')
                ->label('Last Name'),

            Forms\Components\Group::make([
                Forms\Components\Repeater::make('custom_fields')
                    ->schema([
                        Forms\Components\TextInput::make('label')
                            ->label('Field Label'),
                        Forms\Components\TextInput::make('type')
                            ->label('Field Type'),
                        Forms\Components\TextInput::make('name')
                            ->label('Field Name'),
                    ]),
            ]),
        ];
    }
}
