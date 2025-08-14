<?php

namespace App\Filament\Resources\RatingTypes\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\RatingTypes\RatingTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRatingTypes extends ListRecords
{
    protected static string $resource = RatingTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
