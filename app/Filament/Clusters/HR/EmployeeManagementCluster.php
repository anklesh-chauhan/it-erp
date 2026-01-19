<?php

namespace App\Filament\Clusters\HR;

use BackedEnum;
use Filament\Clusters\Cluster;
use Filament\Support\Icons\Heroicon;
use Filament\Pages\Enums\SubNavigationPosition;

class EmployeeManagementCluster extends Cluster
{
    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static ?string $clusterBreadcrumb = 'Employee Management';

    protected static string |\UnitEnum| null $navigationGroup = 'HR';

    protected static ?string $navigationLabel = 'Employee Management';

    protected static ?int $navigationSort = 10;


}
