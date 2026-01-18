<?php

namespace App\Filament\Resources\Concerns;

use Illuminate\Database\Eloquent\Model;

trait HasApprovalLockUI
{
    protected static function approvalLockApplies(Model $record): bool
    {
        return
            method_exists($record, 'approvalApplies')
            && $record->approvalApplies()
            && $record->getApprovalStatus() === 'approved';
    }

    protected static function canEditApproved(Model $record): bool
    {
        if (! static::approvalLockApplies($record)) {
            return true;
        }

        return auth()->user()?->can(
            'OverrideApproval:' . static::permissionKey()
        ) ?? false;
    }

    protected static function canDeleteApproved(Model $record): bool
    {
        if (! static::approvalLockApplies($record)) {
            return true;
        }

        return auth()->user()?->can(
            'OverrideApproval:' . static::permissionKey()
        ) ?? false;
    }
}
