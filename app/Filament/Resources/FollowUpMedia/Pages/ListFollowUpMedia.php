<?php

namespace App\Filament\Resources\FollowUpMediaResource\Pages;

use App\Filament\Resources\FollowUpMediaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFollowUpMedia extends ListRecords
{
    protected static string $resource = FollowUpMediaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
