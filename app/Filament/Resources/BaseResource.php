<?php

namespace App\Filament\Resources;

use Filament\Resources\Resource;
use App\Filament\Resources\Concerns\HasSendForApprovalAction;

abstract class BaseResource extends Resource
{
    use HasSendForApprovalAction;
}
