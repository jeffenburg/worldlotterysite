<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lottery extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'country',
        'description',
        'jackpot',
        'jackpot_currency',
        'jackpot_usd',
        'next_draw_at',
        'active',
        'thelotter_name',
        'thelotter_slug',
        'thelotter_id',
        'main_numbers_count',
        'bonus_numbers_count',
    ];

    protected $casts = [
        'jackpot' => 'decimal:2',
        'jackpot_usd' => 'decimal:2',
        'next_draw_at' => 'datetime',
        'active' => 'boolean',
        'main_numbers_count' => 'integer',
        'bonus_numbers_count' => 'integer',
    ];

    public function draws()
    {
        return $this->hasMany(LotteryDraw::class)->orderByDesc('draw_date');
    }

    // Most recent draw for this lottery, loaded efficiently as a single relation.
    public function latestDraw()
    {
        return $this->hasOne(LotteryDraw::class)->latestOfMany('draw_date');
    }
}