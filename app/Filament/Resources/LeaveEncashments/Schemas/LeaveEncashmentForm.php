<?php

namespace App\Filament\Resources\LeaveEncashments\Schemas;

use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;

class LeaveEncashmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
            ]);
    }
}
