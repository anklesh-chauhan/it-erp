<?php

namespace App\Filament\Resources\FollowUpStatusResource\Pages;

use App\Filament\Resources\FollowUpStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFollowUpStatus extends EditRecord
{
    protected static string $resource = FollowUpStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
