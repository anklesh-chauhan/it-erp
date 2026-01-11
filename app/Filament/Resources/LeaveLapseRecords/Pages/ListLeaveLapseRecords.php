<?php

namespace App\Filament\Resources\LeaveLapseRecords\Pages;

use App\Filament\Resources\LeaveLapseRecords\LeaveLapseRecordResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLeaveLapseRecords extends ListRecords
{
    protected static string $resource = LeaveLapseRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
