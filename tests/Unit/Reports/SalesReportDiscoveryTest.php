<?php

use App\Filament\Pages\Reports\DealPipeline;
use App\Filament\Pages\Reports\LeadPipeline;
use App\Filament\Pages\Reports\OverdueFollowUps;
use App\Filament\Pages\Reports\SalesDocumentRegister;

it('discovers the sales report pages', function () {
    expect(LeadPipeline::isDiscovered())->toBeTrue()
        ->and(DealPipeline::isDiscovered())->toBeTrue()
        ->and(SalesDocumentRegister::isDiscovered())->toBeTrue()
        ->and(OverdueFollowUps::isDiscovered())->toBeTrue();
});
