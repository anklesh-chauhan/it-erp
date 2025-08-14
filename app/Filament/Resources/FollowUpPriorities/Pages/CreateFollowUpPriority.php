<?php

namespace App\Filament\Resources\FollowUpPriorities\Pages;

use App\Filament\Resources\FollowUpPriorities\FollowUpPriorityResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFollowUpPriority extends CreateRecord
{
    protected static string $resource = FollowUpPriorityResource::class;
}
