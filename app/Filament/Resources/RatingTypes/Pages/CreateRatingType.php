<?php

namespace App\Filament\Resources\RatingTypes\Pages;

use App\Filament\Resources\RatingTypes\RatingTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRatingType extends CreateRecord
{
    protected static string $resource = RatingTypeResource::class;
}
