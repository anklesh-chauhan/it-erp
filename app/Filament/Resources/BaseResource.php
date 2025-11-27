<?php

namespace App\Filament\Resources;

use App\Traits\HasSafeGlobalSearch;

use Filament\Resources\Resource;
use App\Filament\Resources\Concerns\HasSendForApprovalAction;

abstract class BaseResource extends Resource
{
    use HasSafeGlobalSearch;
    use HasSendForApprovalAction;
}
