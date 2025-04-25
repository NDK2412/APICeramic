<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class FetchLaravelStats implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        Log::info('Starting FetchLaravelStats');

        $stats = [
            'ram_used' => $this->getRamUsed(),
            'ram_total' => $this->getRamTotal(),
            'cpu_usage' => $this->getCpuUsage(),
            'gpu_usage' => $this->getGpuUsage(),
            'gpu_total' => $this->getGpuTotal(),
            'gpu_used' => $this->getGpuUsed(),
            'php_version' => phpversion(),
            'laravel_version' => \Illuminate\Foundation\Application::VERSION,
            'mysql_version' => $this->getMySqlVersion(),
            'server_uptime' => $this->getServerUptime(),
            'disk_space' => $this->getDiskFreeSpace(),
        ];

        // Tính phần trăm sử dụng
        $ramUsagePercent = $stats['ram_total'] > 0 ? ($stats['ram_used'] / $stats['ram_total']) * 100 : 0;
        $gpuUsagePercent = $stats['gpu_total'] > 0 ? ($stats['gpu_used'] / $stats['gpu_total']) * 100 : 0;

        // Kiểm tra ngưỡng 90%
        if ($stats['cpu_usage'] > 90) {
            Log::emergency('CẢNH BÁO KHẨN CẤP: CPU vượt ngưỡng 90% - Hiện tại: ' . $stats['cpu_usage'] . '%');
        }
        if ($ramUsagePercent > 90) {
            Log::emergency('CẢNH BÁO KHẨN CẤP: RAM vượt ngưỡng 90% - Hiện tại: ' . round($ramUsagePercent, 2) . '%');
        }
        if ($stats['gpu_usage'] > 90 || $gpuUsagePercent > 90) {
            Log::emergency('CẢNH BÁO KHẨN CẤP: GPU vượt ngưỡng 90% - Hiện tại: ' . $stats['gpu_usage'] . '% (Utilization) hoặc ' . round($gpuUsagePercent, 2) . '% (Memory)');
        }

        Log::info('Laravel Stats:', $stats);
        Cache::put('laravel_stats', $stats, 60);
        Log::info('FetchLaravelStats completed');
    }

    private function getRamTotal()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $memory = shell_exec('wmic OS get TotalVisibleMemorySize /Value');
            preg_match('/=(\d+)/', $memory, $matches);
            $result = isset($matches[1]) ? round($matches[1] / 1024, 2) : 0;
            if ($result === 0) {
                Log::warning('getRamTotal failed, using fallback value');
                $result = 16384; // Giá trị mặc định (16GB) nếu không lấy được
            }
            Log::info('getRamTotal result: ' . $result);
            return $result;
        }
        $memory = shell_exec('free -m | awk \'NR==2{print $2}\'');
        $result = $memory ? (int) $memory : 16384; // Fallback 16GB
        Log::info('getRamTotal result: ' . $result);
        return $result;
    }

    private function getRamUsed()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $memory = shell_exec('wmic OS get FreePhysicalMemory /Value');
            preg_match('/=(\d+)/', $memory, $matches);
            $free = isset($matches[1]) ? round($matches[1] / 1024, 2) : 0;
            return $this->getRamTotal() - $free;
        }
        $memory = shell_exec('free -m | awk \'NR==2{print $3}\'');
        return $memory ? (int) $memory : 0;
    }

    private function getCpuUsage()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $load = shell_exec('wmic cpu get loadpercentage');
            preg_match('/(\d+)/', $load, $matches);
            return isset($matches[1]) ? (int) $matches[1] : 0;
        }
        $load = sys_getloadavg();
        return $load && count($load) > 0 ? $load[0] : 0;
    }

    private function getGpuUsage()
    {
        $output = shell_exec('nvidia-smi --query-gpu=utilization.gpu --format=csv,noheader,nounits');
        return $output ? (int) trim($output) : 0;
    }

    private function getGpuTotal()
    {
        $output = shell_exec('nvidia-smi --query-gpu=memory.total --format=csv,noheader,nounits');
        return $output ? (int) trim($output) : 0;
    }

    private function getGpuUsed()
    {
        $output = shell_exec('nvidia-smi --query-gpu=memory.used --format=csv,noheader,nounits');
        return $output ? (int) trim($output) : 0;
    }

    private function getMySqlVersion()
    {
        try {
            return \Illuminate\Support\Facades\DB::select('SELECT VERSION() as version')[0]->version;
        } catch (\Exception $e) {
            Log::error('MySQL Version Error: ' . $e->getMessage());
            return 'Không xác định';
        }
    }

    private function getServerUptime()
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $uptime = shell_exec('net statistics server');
            preg_match('/Statistics since (.*)/', $uptime, $matches);
            return isset($matches[1]) ? $matches[1] : 'Không xác định';
        }
        $uptime = shell_exec('uptime -s');
        return $uptime ? trim($uptime) : 'Không xác định';
    }

    private function getDiskFreeSpace()
    {
        $free = disk_free_space(base_path());
        $total = disk_total_space(base_path());
        return $total ? round(($free / $total) * 100, 2) : 0;
    }
}