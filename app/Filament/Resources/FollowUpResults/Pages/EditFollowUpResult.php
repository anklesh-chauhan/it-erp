<?php

namespace App\Filament\Resources\FollowUpResults\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\FollowUpResults\FollowUpResultResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFollowUpResult extends EditRecord
{
    protected static string $resource = FollowUpResultResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
