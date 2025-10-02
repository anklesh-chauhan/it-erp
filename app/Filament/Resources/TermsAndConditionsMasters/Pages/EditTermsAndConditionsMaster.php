<?php

namespace App\Filament\Resources\TermsAndConditionsMasters\Pages;

use App\Filament\Resources\TermsAndConditionsMasters\TermsAndConditionsMasterResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTermsAndConditionsMaster extends EditRecord
{
    protected static string $resource = TermsAndConditionsMasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
