<?php

use App\Models\Lottery;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Support\Facades\Route;

Route::get('/lotteries', function () {
    return Lottery::where('active', true)->get();
});

Route::get('/lotteries/{slug}', function ($slug) {
    return Lottery::where('slug', $slug)
        ->where('active', true)
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
