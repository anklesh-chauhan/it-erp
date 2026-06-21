<?php

namespace App\Filament\Resources\SampleIssues\Pages;

use App\Filament\Resources\SampleIssues\SampleIssueResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSampleIssues extends ListRecords
{
    protected static string $resource = SampleIssueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
