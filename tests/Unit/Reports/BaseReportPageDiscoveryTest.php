<?php

use App\Filament\Pages\Reports\BaseReportPage;

it('hides the abstract report page from Filament discovery', function () {
    expect(BaseReportPage::isDiscovered())->toBeFalse();
});
