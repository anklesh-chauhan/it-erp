<?php

namespace App\Filament\Resources\RatingTypeResource\Pages;

use App\Filament\Resources\RatingTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRatingType extends EditRecord
{
    protected static string $resource = RatingTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
