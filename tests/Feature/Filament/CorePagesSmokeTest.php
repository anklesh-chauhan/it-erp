<?php

use App\Filament\Pages\MyApprovals;
use App\Filament\Pages\TodaysTour;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Livewire\livewire;

uses(RefreshDatabase::class);

it('renders today\'s visits for an authenticated representative', function () {
    $this->actingAs(reportingUser('TodayTour'));

    livewire(TodaysTour::class)
        ->assertSuccessful();
});

it('renders my approvals with an empty inbox', function () {
    $this->actingAs(reportingUser('MyApprovals'));

    livewire(MyApprovals::class)
        ->assertSuccessful();
});
