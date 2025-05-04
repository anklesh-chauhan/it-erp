<?php

namespace App\Models;

class DealStage extends Status
{
    protected $table = 'deal_stages';

    protected $fillable = ['name', 'color', 'order'];
}
