<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    public const TYPE_GUIDE = 'guide';

    public const TYPE_LOTTERY = 'lottery';

    public const TYPE_HOMEPAGE = 'homepage';

    public const TYPES = [
        self::TYPE_GUIDE => 'Guide (listed on /guides and homepage)',
        self::TYPE_LOTTERY => 'Lottery page content (matched to a lottery by slug)',
        self::TYPE_HOMEPAGE => 'Homepage content',
    ];

    protected $fillable = [
        'title',
        'slug',
        'page_type',
        'excerpt',
        'content',
        'meta_title',
        'meta_description',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
}
