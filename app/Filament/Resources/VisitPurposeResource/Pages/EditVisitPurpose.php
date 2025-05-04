<?php

namespace App\Filament\Resources\VisitPurposeResource\Pages;

use App\Filament\Resources\VisitPurposeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVisitPurpose extends EditRecord
{
    protected static string $resource = VisitPurposeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
