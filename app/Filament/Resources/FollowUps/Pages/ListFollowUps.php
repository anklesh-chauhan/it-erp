<?php

namespace App\Filament\Resources\FollowUps\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\FollowUps\FollowUpResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFollowUps extends ListRecords
{
    protected static string $resource = FollowUpResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
