<?php

namespace App\Filament\Resources\JobRoles\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;

class JobRoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('code')
                    ->required(),
                TextInput::make('level')
                    ->required()
                    ->numeric(),

                Select::make('reports_to_job_role_id')
                        ->label('Reports To Job Role')
                        ->relationship('reportsTo', 'name')
                        ->searchable()
                        ->preload()
                        ->nullable()
                        ->placeholder('Select a reporting job role'),

                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
