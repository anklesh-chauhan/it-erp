<?php

namespace App\Filament\Resources\SgipDistributions\Pages;

use App\Filament\Resources\SgipDistributions\SgipDistributionResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateSgipDistribution extends CreateRecord
{
    protected static string $resource = SgipDistributionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['employee_id'] ??= Auth::user()?->employee_id;

        return $data;
    }
}
