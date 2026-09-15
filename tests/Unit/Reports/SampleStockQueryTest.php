<?php

use App\Filament\Pages\Reports\DoctorSampleLedger;
use App\Filament\Pages\Reports\SgipCompliance;
use App\Filament\Pages\Reports\StockMovementRegister;

it('discovers the sample and stock report pages', function () {
    expect(DoctorSampleLedger::isDiscovered())->toBeTrue()
        ->and(SgipCompliance::isDiscovered())->toBeTrue()
        ->and(StockMovementRegister::isDiscovered())->toBeTrue();
});
