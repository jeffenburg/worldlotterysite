<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LotteryDraw extends Model
{
    protected $fillable = [
        'lottery_id',
        'draw_date',
        'numbers',
        'bonus_numbers',
        'jackpot',
        'jackpot_currency',
        'source_url',
    ];

    protected $casts = [
        'numbers' => 'array',
        'bonus_numbers' => 'array',
        'draw_date' => 'date',
    ];

    public function numbers()
    {
        return $this->hasMany(LotteryDrawNumber::class);
    }
}