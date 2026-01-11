<?php

namespace App\Filament\Resources\LeaveEncashments\Pages;

use App\Filament\Resources\LeaveEncashments\LeaveEncashmentResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditLeaveEncashment extends EditRecord
{
    protected static string $resource = LeaveEncashmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
