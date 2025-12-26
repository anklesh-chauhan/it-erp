<?php

namespace App\Database\Macros;

use Illuminate\Database\Schema\Blueprint;

class BlueprintMacros
{
    public static function register(): void
    {
        Blueprint::macro('blameable', function () {
            /** @var Blueprint $this */
            $this->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $this->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
        });

        Blueprint::macro('blameableSoftDeletes', function () {
            $this->softDeletes();

            $this->foreignId('deleted_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
        });

    }
}
