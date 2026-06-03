<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
use Illuminate\Support\Facades\Schedule;
use App\Http\Controllers\DailyRoutineController;

Schedule::call(function () {

    app(DailyRoutineController::class)
        ->archiveExpiredRoutines();

})->everyMinute();