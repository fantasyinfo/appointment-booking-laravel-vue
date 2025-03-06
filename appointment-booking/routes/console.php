<?php


use Illuminate\Support\Facades\Schedule;

Schedule::command('app:check-reminders')->everyMinute();

