<?php

namespace App\Filament\Resources\Concerns;

use Filament\Actions\BulkAction;
use Filament\Actions\Action;

trait HasSendForApprovalAction
{

    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return parent::table($table)
            ->recordActions([
                ...static::getRecordActions(),
            ])
            ->bulkActions([
                ...static::getBulkActions(),
            ]);
    }

    protected static function getRecordActions(): array
    {
        return [
            Action::make('send_for_approval')
                ->label('Send for Approval')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('primary')
                ->visible(fn ($record) => method_exists($record, 'canSendForApproval') && $record->canSendForApproval())
                ->action(function ($record) {
                    $record->startApprovalFromRules();
                    \Filament\Notifications\Notification::make()
                        ->title('Approval workflow started')
                        ->success()
                        ->send();
                }),
        ];
    }

    protected static function getBulkActions(): array
    {
        return [
            BulkAction::make('bulk_send_for_approval')
                ->label('Send for Approval')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('primary')
                ->requiresConfirmation()
                ->action(function ($records) {
                    foreach ($records as $record) {
                        if (method_exists($record, 'startApprovalFromRules')) {
                            $record->startApprovalFromRules();
                        }
                    }

                    \Filament\Notifications\Notification::make()
                        ->title('Approval started for selected records')
                        ->success()
                        ->send();
                }),
        ];
    }
}
