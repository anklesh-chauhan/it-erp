<?php

namespace App\Filament\Resources\FollowUpMedia\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\FollowUpMedia\FollowUpMediaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFollowUpMedia extends ListRecords
{
    protected static string $resource = FollowUpMediaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
