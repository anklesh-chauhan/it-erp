<?php

namespace App\Filament\Resources\LeaveAdjustments\Pages;

use App\Filament\Resources\LeaveAdjustments\LeaveAdjustmentResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditLeaveAdjustment extends EditRecord
{
    protected static string $resource = LeaveAdjustmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
