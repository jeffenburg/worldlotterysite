<?php

use App\Models\LinkClick;
use App\Models\Lottery;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/lotteries', function () {
    return Lottery::where('active', true)
        ->with('latestDraw.numbers')
        ->orderByDesc('jackpot_usd')
        ->get();
});

Route::get('/lotteries/{slug}', function ($slug) {
    return Lottery::where('slug', $slug)
        ->where('active', true)
        ->with(['draws' => function ($query) {
            $query->with('numbers')->limit(8);
        }])
        ->firstOrFail();
});

Route::get('/pages', function () {
    return Page::where('active', true)->get();
});

Route::get('/pages/{slug}', function ($slug) {
    return Page::where('slug', $slug)
        ->where('active', true)
        ->firstOrFail();
});

Route::get('/posts', function () {
    return Post::where('active', true)->get();
});

Route::get('/posts/{slug}', function ($slug) {
    return Post::where('slug', $slug)
        ->where('active', true)
        ->firstOrFail();
});

// Called server-side by the Next.js /go/{slug} redirect handler.
Route::post('/clicks', function (Request $request) {
    $data = $request->validate([
        'slug' => ['required', 'string', 'max:255'],
        'placement' => ['nullable', 'string', 'max:255'],
        'target_url' => ['required', 'url', 'max:2048'],
        'ip' => ['nullable', 'ip'],
        'country' => ['nullable', 'string', 'size:2'],
        'region' => ['nullable', 'string', 'max:255'],
        'city' => ['nullable', 'string', 'max:255'],
        'user_agent' => ['nullable', 'string', 'max:2000'],
        'referer' => ['nullable', 'string', 'max:2048'],
    ]);

    LinkClick::create($data);

    return response()->noContent();
})->middleware('throttle:120,1');
