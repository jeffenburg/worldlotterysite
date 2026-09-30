<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LinkClick extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'slug',
        'placement',
        'target_url',
        'ip',
        'country',
        'region',
        'city',
        'user_agent',
        'referer',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
