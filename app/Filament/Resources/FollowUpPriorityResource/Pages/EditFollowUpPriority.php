<?php

namespace App\Filament\Resources\FollowUpPriorityResource\Pages;

use App\Filament\Resources\FollowUpPriorityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFollowUpPriority extends EditRecord
{
    protected static string $resource = FollowUpPriorityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
