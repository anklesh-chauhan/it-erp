<?php

namespace App\Filament\Resources\TravelSegments\Pages;

use App\Filament\Resources\TravelSegments\TravelSegmentResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditTravelSegment extends EditRecord
{
    protected static string $resource = TravelSegmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
