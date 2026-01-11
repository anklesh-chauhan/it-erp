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
                TextEntry::make('approval_rule_id')
                    ->label('Approval Rule ID')
                    ->content(fn ($record) => $record->approval_rule_id),

                TextEntry::make('approver_id')
                    ->label('Approver ID')
                    ->content(fn ($record) => $record->approver_id),

                TextEntry::make('model_type')
                    ->label('Model Type')
                    ->content(fn ($record) => $record->model_type),

                TextEntry::make('model_id')
                    ->label('Model ID')
                    ->content(fn ($record) => $record->model_id),

                TextEntry::make('approval_status')
                    ->label('Status')
                    ->content(fn ($record) => ucfirst($record->approval_status)),

                TextEntry::make('remarks')
                    ->label('Remarks')
                    ->content(fn ($record) => $record->remarks),
            ]);
    }
}
