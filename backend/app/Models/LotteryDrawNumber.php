<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LotteryDrawNumber extends Model
{
    protected $fillable = [
        'lottery_draw_id',
        'type',
        'number',
        'position',
    ];
}