<?php

use App\Filament\Pages\PunchIn;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Livewire\livewire;

uses(RefreshDatabase::class);

it('shows the punch in action for an employee with no attendance today', function () {
    $user = reportingUser('PunchRep');

    $this->actingAs($user);

    livewire(PunchIn::class)
        ->assertSuccessful()
        ->assertActionExists('punchIn');
});
