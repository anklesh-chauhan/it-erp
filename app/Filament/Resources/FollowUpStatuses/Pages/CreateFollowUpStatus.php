<?php

namespace App\Filament\Resources\FollowUpStatuses\Pages;

use App\Filament\Resources\FollowUpStatuses\FollowUpStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFollowUpStatus extends CreateRecord
{
    protected static string $resource = FollowUpStatusResource::class;
}
