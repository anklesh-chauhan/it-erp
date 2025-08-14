<?php

namespace App\Filament\Resources\FollowUpResults\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\FollowUpResults\FollowUpResultResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFollowUpResults extends ListRecords
{
    protected static string $resource = FollowUpResultResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
