<?php

namespace App\Filament\Resources\LeaveApplications\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;

class InstancesRelationManager extends RelationManager
{
    protected static string $relationship = 'instances';
    protected static ?string $title = 'Leave Instances';

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')->date(),
                Tables\Columns\TextColumn::make('pay_factor'),
                Tables\Columns\TextColumn::make('approval_status')->badge(),
            ])
            ->defaultSort('date');
    }
}
