<?php

use App\Console\Commands\GenerateStatements;
use Illuminate\Support\Facades\Schedule;


Schedule::command(GenerateStatements::class);
