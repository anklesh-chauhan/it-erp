<?php

namespace App\Filament\Resources\FollowUpPriorityResource\Pages;

use App\Filament\Resources\FollowUpPriorityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFollowUpPriorities extends ListRecords
{
    protected static string $resource = FollowUpPriorityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
