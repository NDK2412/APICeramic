<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\Classification;
use Illuminate\Support\Facades\Log;

class PredictionController extends Controller
{
    public function predict(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Bạn cần đăng nhập để sử dụng tính năng này.'], 401);
        }

        $user = Auth::user();

        if (!$request->hasFile('file')) {
            return response()->json(['error' => 'Vui lòng upload ảnh trước!'], 400);
        }

        if ($user->tokens <= 0) {
            return response()->json(['error' => 'Bạn đã hết lượt dự đoán! Vui lòng nạp thêm.'], 403);
        }

        $file = $request->file('file');
        $formData = new \Illuminate\Http\UploadedFile($file->path(), $file->getClientOriginalName());

        try {
            $apiKey = env('FASTAPI_KEY');
            Log::info('Sending request to FastAPI', [
                'url' => 'http://localhost:60074/predict',
                'api_key' => $apiKey,
                'file_name' => $formData->getClientOriginalName(),
                'file_path' => $formData->path(),
            ]);

            // Sửa tên header thành 'api-key'
            $response = Http::withHeaders([
                'api-key' => $apiKey // Thay 'api_key' thành 'api-key'
            ])
            ->attach('file', file_get_contents($formData->path()), $formData->getClientOriginalName())
            ->post('http://localhost:60074/predict');

            Log::info('API Response', [
                'status' => $response->status(),
                'headers' => $response->headers(),
                'body' => $response->body(),
            ]);

            // Kiểm tra nếu phản hồi là HTML
            $body = $response->body();
            if (stripos($body, '<!DOCTYPE') !== false || stripos($body, '<html') !== false) {
                Log::error('API returned HTML instead of JSON', ['body' => substr($body, 0, 200)]);
                return response()->json([
                    'error' => 'API trả về HTML thay vì JSON: ' . substr($body, 0, 100)
                ], 500);
            }

            if ($response->status() !== 200) {
                Log::error('API returned non-200 status', ['status' => $response->status(), 'body' => $body]);
                return response()->json(['error' => 'API trả về lỗi: ' . $body], $response->status());
            }

            $predictData = $response->json();

            if (!$predictData || !is_array($predictData)) {
                Log::error('Invalid API response', ['response' => $predictData]);
                return response()->json(['error' => 'Phản hồi từ API không hợp lệ'], 500);
            }

            if (isset($predictData['error'])) {
                return response()->json(['error' => $predictData['error']], 500);
            }

            if (!isset($predictData['predicted_class']) || !isset($predictData['llm_response'])) {
                Log::error('Missing keys in API response', ['response' => $predictData]);
                return response()->json(['error' => 'Dữ liệu từ API thiếu predicted_class hoặc llm_response'], 500);
            }

            $user->tokens -= 1;
            $user->save();

            $classification = new Classification();
            $imagePath = $file->store('images', 'public');
            $classification->user_id = $user->id;
            $classification->image_path = '/storage/' . $imagePath;
            $classification->result = $predictData['predicted_class'];
            $classification->llm_response = $predictData['llm_response'];
            $classification->save();

            return response()->json([
                'success' => true,
                'predicted_class' => $predictData['predicted_class'],
                'llm_response' => $predictData['llm_response'],
                'tokens' => $user->tokens,
            ]);

        } catch (\Exception $e) {
            Log::error('Prediction error', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Lỗi khi kết nối với server: ' . $e->getMessage()], 500);
        }
    }
}