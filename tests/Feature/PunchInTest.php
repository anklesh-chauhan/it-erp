<?php

use App\Filament\Pages\PunchIn;
use App\Models\User;
use function Pest\Livewire\livewire;

it('automatically mounts the punchIn action on page load', function () {
    $user = User::factory()->create();

    // If the action requires an employee relation, create one here:
    // $user->employee()->create([...]);

    livewire(PunchIn::class)
        ->assertSuccessful()
        ->assertActionMounted('punchIn');
});
