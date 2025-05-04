<?php

namespace App\Filament\Resources\FollowUpStatusResource\Pages;

use App\Filament\Resources\FollowUpStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFollowUpStatuses extends ListRecords
{
    protected static string $resource = FollowUpStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
