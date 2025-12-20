<?php

use Illuminate\Support\Facades\File;

it('requires email in UserResource form', function () {
    $path = base_path('app/Filament/Resources/Users/UserResource.php');
    $this->assertFileExists($path);

    $content = File::get($path);

    expect(str_contains($content, "TextInput::make('email')"))->toBeTrue();
    expect(str_contains($content, "->required()"))->toBeTrue();
});

