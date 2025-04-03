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
}