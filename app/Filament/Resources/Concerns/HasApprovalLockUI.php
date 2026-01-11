<?php

namespace App\Filament\Resources\Concerns;

use Illuminate\Database\Eloquent\Model;

trait HasApprovalLockUI
{
    protected static function canEditApproved(Model $record): bool
    {
        if ($record->approval_status !== 'approved') {
            return true;
        }

        return auth()->user()
            ?->can('OverrideApproval:' . class_basename($record)) ?? false;
    }

    protected static function canDeleteApproved(Model $record): bool
    {
        if ($record->approval_status !== 'approved') {
            return true;
        }

        return auth()->user()
            ?->can('OverrideApproval:' . class_basename($record)) ?? false;
    }
}
