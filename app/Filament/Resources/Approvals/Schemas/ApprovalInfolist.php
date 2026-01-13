<?php

namespace App\Filament\Resources\Approvals\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ApprovalInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('approvable_type')
                    ->label('Module')
                    ->formatStateUsing(fn ($state) => class_basename($state)),

                TextEntry::make('approvable_id')
                    ->label('Document No')
                    ->formatStateUsing(
                        fn ($state, $record) =>
                            $record->getDocumentNumber() ?? $state
                    ),

                TextEntry::make('requester.name')
                    ->label('Requested By'),

                TextEntry::make('approval_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'draft'    => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default    => 'gray',
                    }),

                TextEntry::make('created_at')
                    ->label('Requested At')
                    ->dateTime(),

                TextEntry::make('completed_at')
                    ->label('Completed At')
                    ->dateTime()
                    ->placeholder('—'),
            ]);
    }
}
