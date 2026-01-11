<?php

namespace App\Filament\Resources\PayrollLeaveSnapshots\Pages;

use App\Filament\Resources\PayrollLeaveSnapshots\PayrollLeaveSnapshotResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPayrollLeaveSnapshots extends ListRecords
{
    protected static string $resource = PayrollLeaveSnapshotResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
