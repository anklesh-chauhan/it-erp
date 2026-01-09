<?php

namespace App\Filament\Resources\SalesTourPlans\Tables;

use App\Filament\Actions\ApprovalAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Actions\Action;
use Illuminate\Database\Eloquent\Builder;

class SalesTourPlansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sales_employee')
                    ->label('Sales Employee')
                    ->getStateUsing(fn ($record) =>
                        $record->user?->employee?->full_name
                            ?? $record->user?->email
                    )
                    ->sortable(
                        query: function (Builder $query, string $direction): Builder {
                            return $query
                                ->leftJoin('users', 'users.id', '=', 'sales_tour_plans.user_id')
                                ->leftJoin('employees', 'employees.login_id', '=', 'users.id')
                                ->orderBy('employees.first_name', $direction)
                                ->select('sales_tour_plans.*');
                        }
                    )
                    ->searchable(
                        query: function (Builder $query, string $search): Builder {
                            return $query->whereHas('user.employee', function ($q) use ($search) {
                                $q->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                            });
                        }
                    ),

                Tables\Columns\TextColumn::make('month')->label('Month')->sortable()->date('F Y'),
                Tables\Columns\TextColumn::make('status')
                    ->colors([
                        'secondary' => 'draft',
                        'warning' => 'submitted',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ])->sortable()->searchable()->label('Status')->badge(),
                Tables\Columns\TextColumn::make('approvedBy.name')->label('Approved By'),
                Tables\Columns\TextColumn::make('approved_at')->dateTime()->label('Approved At'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                ApprovalAction::make(),

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
