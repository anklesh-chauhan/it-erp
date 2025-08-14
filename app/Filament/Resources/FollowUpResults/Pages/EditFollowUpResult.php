<?php

namespace App\Filament\Resources\FollowUpResultResource\Pages;

use App\Filament\Resources\FollowUpResultResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFollowUpResult extends EditRecord
{
    protected static string $resource = FollowUpResultResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
