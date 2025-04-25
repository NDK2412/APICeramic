<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class FetchFastApiStats implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        Log::info('Starting FetchFastApiStats');
        try {
            $response = Http::withHeaders(['api-key' => env('FASTAPI_KEY')])
                ->timeout(5)
                // ->get('http://localhost:60074/system-stats');
                ->get('http://localhost:55001/system-stats');

            if ($response->successful()) {
                $stats = $response->json();
            } else {
                $stats = [
                    'cpu_usage_percent' => 0,
                    'ram_total_mb' => 0,
                    'ram_used_mb' => 0,
                    'ram_usage_percent' => 0,
                    'gpu_usage_percent' => 0,
                    'gpu_total_mb' => 0,
                    'gpu_used_mb' => 0,
                    'error' => 'Không lấy được thông tin từ FastAPI: ' . $response->status()
                ];
            }
        } catch (\Exception $e) {
            $stats = [
                'cpu_usage_percent' => 0,
                'ram_total_mb' => 0,
                'ram_used_mb' => 0,
                'ram_usage_percent' => 0,
                'gpu_usage_percent' => 0,
                'gpu_total_mb' => 0,
                'gpu_used_mb' => 0,
                'error' => 'Lỗi kết nối FastAPI: ' . $e->getMessage()
            ];
        }

        // Kiểm tra ngưỡng 90%
        if ($stats['cpu_usage_percent'] > 90) {
            Log::emergency('CẢNH BÁO KHẨN CẤP: CPU (FastAPI) vượt ngưỡng 90% - Hiện tại: ' . $stats['cpu_usage_percent'] . '%');
        }
        if ($stats['ram_usage_percent'] > 10) {
            Log::emergency('CẢNH BÁO KHẨN CẤP: RAM (FastAPI) vượt ngưỡng 90% - Hiện tại: ' . $stats['ram_usage_percent'] . '%');
        }
        if ($stats['gpu_usage_percent'] > 90) {
            Log::emergency('CẢNH BÁO KHẨN CẤP: GPU (FastAPI) vượt ngưỡng 90% - Hiện tại: ' . $stats['gpu_usage_percent'] . '%');
        }

        Log::info('FastAPI Stats:', $stats);
        Cache::put('fastapi_stats', $stats, 60);
        Log::info('FetchFastApiStats completed');
    }
}