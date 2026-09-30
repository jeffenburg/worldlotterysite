<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ClickController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LotteryController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.attempt');
    });

    Route::middleware('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/', DashboardController::class)->name('dashboard');

        Route::resource('lotteries', LotteryController::class)->except('show');
        Route::resource('pages', PageController::class)->except('show');
        Route::resource('posts', PostController::class)->except('show');
        Route::get('clicks', [ClickController::class, 'index'])->name('clicks.index');
    });
});
