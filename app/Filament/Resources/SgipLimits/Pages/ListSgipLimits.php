<?php

namespace App\Filament\Resources\SgipLimits\Pages;

use App\Filament\Resources\SgipLimits\SgipLimitResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSgipLimits extends ListRecords
{
    protected static string $resource = SgipLimitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
