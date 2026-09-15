<?php

use App\Filament\Pages\Reports\AttendanceRegister;
use App\Filament\Pages\Reports\LeaveLedger;
use App\Filament\Pages\Reports\PunchVsTour;

it('discovers the HR report pages', function () {
    expect(AttendanceRegister::isDiscovered())->toBeTrue()
        ->and(PunchVsTour::isDiscovered())->toBeTrue()
        ->and(LeaveLedger::isDiscovered())->toBeTrue();
});
