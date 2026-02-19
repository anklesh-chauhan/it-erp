<?php

namespace App\Filament\Resources\SalesDcrs\Schemas;

use App\Models\SalesDcr;
use Filament\Forms;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Components\MorphToSelect\Type;
use App\Models\AccountMaster as Customer;
use App\Models\Lead;
use App\Models\VisitFeedbackQuestion;
use App\Models\VisitOutcome;
use Dotenv\Util\Str;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ViewField;
use Filament\Schemas\Components\Utilities\Get;

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
                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'submitted' => 'Submitted',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                            ])->default('draft'), //
                    ])
                    ->columns(3),
                Group::make()
                    ->schema([
                        Forms\Components\Repeater::make('visits')
                            ->relationship()
                            ->schema([
                                Select::make('visitable_type')
                                    ->label('Visit Type')
                                    ->options([
                                        Customer::class => 'Customer',
                                        Lead::class => 'Lead',
                                    ])
                                    ->default(Customer::class)
                                    ->required(),

                                Select::make('visitable_id')
                                    ->label('Visit To')
                                    ->options(fn (Get $get) =>
                                        $get('visitable_type') === Customer::class
                                            ? Customer::pluck('name', 'id')
                                            : Lead::pluck('name', 'id')
                                    )
                                    ->searchable()
                                    ->required(),
                                Forms\Components\TimePicker::make('check_in_at'),
                                Forms\Components\TimePicker::make('check_out_at'),
                                Forms\Components\Select::make('visit_outcome_id')
                                    ->label('Visit Outcome')
                                    ->options(
                                        VisitOutcome::query()
                                            ->orderBy('label')
                                            ->pluck('label', 'id')
                                    )
                                    ->searchable()
                                    ->required(),
                                Forms\Components\Toggle::make('is_joint_work'),
                            ])
                            ->grid(2)
                            ->columns(4),


                    ])->columnSpanFull(),



            ]);
    }
}
