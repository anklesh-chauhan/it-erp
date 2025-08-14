<?php

namespace App\Filament\Resources\FollowUpStatuses\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\FollowUpStatuses\FollowUpStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFollowUpStatus extends EditRecord
{
    protected static string $resource = FollowUpStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
