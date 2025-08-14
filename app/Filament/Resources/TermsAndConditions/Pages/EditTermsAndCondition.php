<?php

namespace App\Filament\Resources\TermsAndConditions\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\TermsAndConditions\TermsAndConditionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTermsAndCondition extends EditRecord
{
    protected static string $resource = TermsAndConditionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
