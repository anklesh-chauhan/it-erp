<?php

namespace App\Filament\Resources\FollowUpMedia\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\FollowUpMedia\FollowUpMediaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFollowUpMedia extends EditRecord
{
    protected static string $resource = FollowUpMediaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
