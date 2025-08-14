<?php

namespace App\Filament\Resources\FollowUpPriorities\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\FollowUpPriorities\FollowUpPriorityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFollowUpPriorities extends ListRecords
{
    protected static string $resource = FollowUpPriorityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
