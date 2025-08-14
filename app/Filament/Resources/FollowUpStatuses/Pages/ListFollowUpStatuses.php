<?php

namespace App\Filament\Resources\FollowUpStatuses\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\FollowUpStatuses\FollowUpStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFollowUpStatuses extends ListRecords
{
    protected static string $resource = FollowUpStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
