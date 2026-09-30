<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lottery;
use App\Models\Page;
use App\Models\Post;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('admin.dashboard', [
            'counts' => [
                'lotteries' => Lottery::count(),
                'activeLotteries' => Lottery::where('active', true)->count(),
                'pages' => Page::count(),
                'posts' => Post::count(),
            ],
            'recentPosts' => Post::latest('updated_at')->limit(5)->get(),
            'recentLotteries' => Lottery::latest('updated_at')->limit(5)->get(),
        ]);
    }
}
