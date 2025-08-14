<?php

namespace App\Filament\Resources\VisitPurposes\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\VisitPurposes\VisitPurposeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVisitPurpose extends EditRecord
{
    protected static string $resource = VisitPurposeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
