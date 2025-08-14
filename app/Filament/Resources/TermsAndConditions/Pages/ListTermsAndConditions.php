<?php

namespace App\Filament\Resources\TermsAndConditions\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\TermsAndConditions\TermsAndConditionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTermsAndConditions extends ListRecords
{
    protected static string $resource = TermsAndConditionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
