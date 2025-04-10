<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\FetchLaravelStats;
use App\Jobs\FetchFastApiStats;
use App\Models\Setting;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $this->runScheduledTasks();
        })->everyFiveSeconds();
    }

    public function runScheduledTasks()
    {
        $isEnabled = Setting::where('key', 'system_info_optimization')->first()?->value === 'enabled';
        \Illuminate\Support\Facades\Log::info('Scheduler running, isEnabled: ' . ($isEnabled ? 'true' : 'false'));
        if ($isEnabled) {
            FetchLaravelStats::dispatch();
            FetchFastApiStats::dispatch();
            \Illuminate\Support\Facades\Log::info('Jobs dispatched');
        } else {
            \Illuminate\Support\Facades\Log::info('Jobs not dispatched because system_info_optimization is disabled');
        }
    }

    protected $commands = [];
}