<?php

namespace App\Http\Controllers;

use App\Models\Ceramic;
use Illuminate\Http\Request;

class CeramicController extends Controller
{
    public function gallery(Request $request)
    {
        // Lấy tham số lọc từ request
        $category = $request->query('category');
        $origin = $request->query('origin');

        // Xây dựng truy vấn
        $query = Ceramic::query();

        // Áp dụng bộ lọc
        if ($category) {
            $query->where('category', $category);
        }

        if ($origin) {
            $query->where('origin', $origin);
        }

        // Phân trang (10 món đồ gốm mỗi trang)
        $ceramics = $query->paginate(10);

        // Lấy danh sách danh mục và nguồn gốc duy nhất cho bộ lọc
        $categories = Ceramic::select('category')->distinct()->pluck('category');
        $origins = Ceramic::select('origin')->distinct()->pluck('origin');

        return view('gallery', compact('ceramics', 'categories', 'origins'));
    }

    public function show($id)
    {
        $ceramic = Ceramic::findOrFail($id);
        return view('ceramic_detail', compact('ceramic'));
    }

    public function classify(Request $request)
{
    try {
        // Xác thực dữ liệu đầu vào
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'result' => 'required|string', // Thêm xác thực cho kết quả nhận diện
        ]);

        // Kiểm tra người dùng đã đăng nhập chưa
        $userId = auth()->id();
        if (!$userId) {
            return response()->json(['error' => 'Người dùng chưa đăng nhập'], 401);
        }

        // Lưu ảnh vào storage
        $imagePath = $request->file('image')->store('images', 'public');
        if (!$imagePath) {
            return response()->json(['error' => 'Không thể lưu ảnh'], 500);
        }

        // Lấy kết quả nhận diện từ request
        $result = $request->input('result');

        // Lưu vào bảng classifications
        $classification = \App\Models\Classification::create([
            'user_id' => $userId,
            'image_path' => '/storage/' . $imagePath,
            'result' => $result,
            'created_at' => now(),
        ]);

        return response()->json([
            'message' => 'Nhận diện thành công và đã lưu vào lịch sử',
            'result' => $result,
            'classification_id' => $classification->id,
        ], 200);

    } catch (\Exception $e) {
        \Log::error('Lỗi khi lưu nhận diện: ' . $e->getMessage());
        return response()->json(['error' => 'Có lỗi xảy ra khi lưu nhận diện: ' . $e->getMessage()], 500);
    }
}
}

    
