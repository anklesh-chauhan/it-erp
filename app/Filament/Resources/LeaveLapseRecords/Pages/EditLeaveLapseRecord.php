<?php

namespace App\Filament\Resources\LeaveLapseRecords\Pages;

use App\Filament\Resources\LeaveLapseRecords\LeaveLapseRecordResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditLeaveLapseRecord extends EditRecord
{
    protected static string $resource = LeaveLapseRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
