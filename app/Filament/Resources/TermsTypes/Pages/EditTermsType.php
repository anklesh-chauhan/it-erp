<?php

namespace App\Filament\Resources\TermsTypes\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\TermsTypes\TermsTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTermsType extends EditRecord
{
    protected static string $resource = TermsTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
