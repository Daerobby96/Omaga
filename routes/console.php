<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule untuk reminder otomatis
Schedule::command('reminder:logbook --days=3')->dailyAt('08:00');
Schedule::command('reminder:selesai --days=7')->dailyAt('09:00');
