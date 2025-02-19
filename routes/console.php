<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('reports:notifiche5-giorni')->dailyAt('02:00');
Schedule::command('reports:notifiche7-giorni')->dailyAt('02:30');
Schedule::command('reports:notifiche85-giorni')->dailyAt('03:00');
Schedule::command('reports:notifiche90-giorni')->dailyAt('03:30');
