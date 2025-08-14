<?php

namespace App\Filament\Resources\RatingTypes\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\RatingTypes\RatingTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRatingType extends EditRecord
{
    protected static string $resource = RatingTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
