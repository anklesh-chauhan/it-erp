<?php

namespace App\Filament\Resources\DeliveryChallans\Pages;

use App\Filament\Resources\DeliveryChallans\DeliveryChallanResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDeliveryChallans extends ListRecords
{
    protected static string $resource = DeliveryChallanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
