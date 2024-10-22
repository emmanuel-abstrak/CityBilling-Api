<?php

use App\Console\Commands\GenerateStatements;
use App\Console\Commands\PaymentStatus;
use Illuminate\Support\Facades\Schedule;


Schedule::command(GenerateStatements::class)->lastDayOfMonth();
Schedule::command(PaymentStatus::class)->everyThirtySeconds();
