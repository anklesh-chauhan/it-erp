<?php

namespace App\Filament\Resources\Approvals\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Textarea;

class ApprovalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('approvable_type')
                    ->label('Model Type')
                    ->disabled(),

                TextInput::make('approvable_id')
                    ->label('Record ID')
                    ->disabled(),

                Select::make('requested_by')
                    ->relationship('requester', 'name')
                    ->label('Requested By')
                    ->disabled(),

                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->required(),

                DateTimePicker::make('completed_at')
                    ->label('Completed At')
                    ->nullable(),
            ]);
    }
}
