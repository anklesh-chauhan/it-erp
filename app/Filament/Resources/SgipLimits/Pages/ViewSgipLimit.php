<?php

namespace App\Filament\Resources\SgipLimits\Pages;

use App\Filament\Resources\SgipLimits\SgipLimitResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSgipLimit extends ViewRecord
{
    protected static string $resource = SgipLimitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
