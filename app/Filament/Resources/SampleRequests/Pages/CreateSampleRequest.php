<?php

namespace App\Filament\Resources\SampleRequests\Pages;

use App\Filament\Resources\SampleRequests\SampleRequestResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSampleRequest extends CreateRecord
{
    protected static string $resource = SampleRequestResource::class;
}
