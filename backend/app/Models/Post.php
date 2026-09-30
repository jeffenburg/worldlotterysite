<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'image_url',
        'meta_title',
        'meta_description',
        'published_at',
        'active',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'active' => 'boolean',
    ];
}
