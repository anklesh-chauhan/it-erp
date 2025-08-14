<?php

namespace App\Filament\Resources\Patches\Pages;

use App\Filament\Resources\Patches\PatchResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePatch extends CreateRecord
{
    protected static string $resource = PatchResource::class;
}
