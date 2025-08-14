<?php

namespace App\Filament\Resources\Leads\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Leads\LeadResource;
use Filament\Forms;

class ViewLead extends ViewRecord
{
    protected static string $resource = LeadResource::class;

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('first_name')
                ->label('First Name'),

            TextInput::make('last_name')
                ->label('Last Name'),

            Group::make([
                Repeater::make('custom_fields')
                    ->schema([
                        TextInput::make('label')
                            ->label('Field Label'),
                        TextInput::make('type')
                            ->label('Field Type'),
                        TextInput::make('name')
                            ->label('Field Name'),
                    ]),
            ]),
        ];
    }
}
