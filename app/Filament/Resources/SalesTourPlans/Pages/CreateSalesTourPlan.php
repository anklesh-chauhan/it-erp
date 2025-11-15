<?php

namespace App\Filament\Resources\SalesTourPlans\Pages;

use App\Filament\Resources\SalesTourPlans\SalesTourPlanResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateSalesTourPlan extends CreateRecord
{
    protected static string $resource = SalesTourPlanResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = Auth::user()->name ?? 'System';
        return $data;
    }
}
