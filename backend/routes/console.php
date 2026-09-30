<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('lotteries:jackpots')
    ->everyFifteenMinutes();

Schedule::command('lotteries:results')
    ->everyFifteenMinutes();