<?php

namespace App\Filament\Resources\FollowUpPriorities\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\FollowUpPriorities\FollowUpPriorityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFollowUpPriority extends EditRecord
{
    protected static string $resource = FollowUpPriorityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
