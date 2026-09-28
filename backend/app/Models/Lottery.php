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
        'thelotter_url',
        'thelotter_id',
        'main_numbers_count',
        'bonus_numbers_count',
    ];
}