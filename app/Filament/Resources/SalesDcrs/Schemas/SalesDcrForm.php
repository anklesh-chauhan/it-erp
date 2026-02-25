<?php

namespace App\Filament\Resources\SalesDcrs\Schemas;

use Filament\Forms;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;

class SalesDcrForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([
                        Forms\Components\DatePicker::make('dcr_date')
                            ->required()
                            ->default(now()), //
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required(), //
                        Forms\Components\Select::make('approval_status')
                            ->options([
                                'draft' => 'Draft',
                                'submitted' => 'Submitted',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                            ])->default('draft'), //
                    ])
                    ->columns(3),
            ]);
    }
}
