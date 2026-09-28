<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LotteryDraw extends Model
{
    protected $fillable = [
        'lottery_id',
        'draw_date',
        'jackpot',
        'jackpot_currency',
        'source_url',
    ];

    protected $casts = [
        'jackpot' => 'decimal:2',
        'draw_date' => 'date',
    ];

    public function lottery()
    {
        return $this->belongsTo(Lottery::class);
    }

    public function numbers()
    {
        return $this->hasMany(LotteryDrawNumber::class)->orderBy('position');
    }
}