<?php

namespace App\Filament\Resources\TermsAndConditionsMasters\Pages;

use App\Filament\Resources\TermsAndConditionsMasters\TermsAndConditionsMasterResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTermsAndConditionsMasters extends ListRecords
{
    protected static string $resource = TermsAndConditionsMasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
