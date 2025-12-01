<?php

namespace App\Filament\Actions;

use Filament\Actions\BulkAction;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class BulkApprovalAction extends BulkAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Send for Bulk Approval');
        $this->icon('heroicon-o-arrow-up-tray');
        $this->color('primary');

        $this->action(function (Collection $records, Component $livewire) {

            $success = 0;
            $skipped = 0;
            $unsupported = 0;

            foreach ($records as $record) {

                // ---------------------------
                // 1. Model does not support workflow
                // ---------------------------
                if (!method_exists($record, 'startApprovalFromRules')) {
                    $unsupported++;
                    continue;
                }

                // ---------------------------
                // 2. If model provides canStartApproval() → use it
                // ---------------------------
                if (method_exists($record, 'canStartApproval')) {
                    if (!$record->canStartApproval()) {
                        $skipped++;
                        continue;
                    }
                }

                // ---------------------------
                // 3. Safety Check — approval_status column exists
                // ---------------------------
                if (isset($record->approval_status)) {
                    if (!in_array($record->approval_status, ['draft', 'created', 'pending'])) {
                        $skipped++;
                        continue;
                    }
                }

                // ---------------------------
                // 4. Safety Check — approval_started_at timestamp
                // ---------------------------
                if (isset($record->approval_started_at) && !empty($record->approval_started_at)) {
                    $skipped++;
                    continue;
                }

                // ---------------------------
                // 5. Finally, start approval
                // ---------------------------
                $record->startApprovalFromRules();
                $success++;
            }

            // Refresh sidebar count
            $livewire->dispatch('refresh-sidebar');

            Notification::make()
                ->title('Bulk Approval Summary')
                ->body("
                    ✔️ <b>{$success}</b> sent for approval<br>
                    ⚠️ <b>{$skipped}</b> skipped<br>
                    ❌ <b>{$unsupported}</b> not supported
                ")
                ->success()
                ->send();
        });
    }

    public static function make(?string $name = null): static
    {
        return parent::make($name ?? 'send_for_bulk_approval');
    }
}
