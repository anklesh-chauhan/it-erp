<?php

namespace App\Filament\Resources\Positions\Pages;

use App\Filament\Resources\Positions\PositionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\EmploymentDetail;
use App\Models\Position;

class CreatePosition extends CreateRecord
{
    protected static string $resource = PositionResource::class;

}
