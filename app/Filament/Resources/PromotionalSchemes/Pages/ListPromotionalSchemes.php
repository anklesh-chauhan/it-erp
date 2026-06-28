<?php

namespace App\Filament\Resources\PromotionalSchemes\Pages;

use App\Filament\Resources\PromotionalSchemes\PromotionalSchemeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPromotionalSchemes extends ListRecords
{
    protected static string $resource = PromotionalSchemeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
