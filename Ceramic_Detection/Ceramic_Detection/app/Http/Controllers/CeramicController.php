<?php

namespace App\Http\Controllers;

use App\Models\Ceramic;
use Illuminate\Http\Request;
/**
 * @OA\Info(
 *     title="Ceramic Detection API",
 *     version="1.0.0",
 *     description="API documentation for the Ceramic Detection application"
 * )
 */

class CeramicController extends Controller
{
    /**
     * @OA\Get(
     *     path="/gallery",
     *     tags={"Ceramic"},
     *     summary="Get ceramic gallery",
     *     description="Retrieve a paginated list of ceramics with optional category and origin filters",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="category",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", description="Filter by ceramic category")
     *     ),
     *     @OA\Parameter(
     *         name="origin",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", description="Filter by ceramic origin")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="ceramics", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="categories", type="array", @OA\Items(type="string")),
     *             @OA\Property(property="origins", type="array", @OA\Items(type="string"))
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/ceramic/{id}",
     *     tags={"Ceramic"},
     *     summary="Get ceramic details",
     *     description="Retrieve details of a specific ceramic by ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", description="Ceramic ID")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="ceramic", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Ceramic not found")
     * )
     */
    public function show($id)
    {
        $ceramic = Ceramic::findOrFail($id);
        return view('ceramic_detail', compact('ceramic'));
    }

    /**
     * @OA\Post(
     *     path="/ceramic/classify",
     *     tags={"Ceramic"},
     *     summary="Classify ceramic image",
     *     description="Upload an image and classify it, saving the result to classification history",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="image",
     *                     type="string",
     *                     format="binary",
     *                     description="Ceramic image file (JPEG, PNG, JPG, GIF, max 2MB)"
     *                 ),
     *                 @OA\Property(
     *                     property="result",
     *                     type="string",
     *                     description="Classification result"
     *                 ),
     *                 @OA\Property(
     *                     property="llm_response",
     *                     type="string",
     *                     description="LLM response for the classification"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="result", type="string"),
     *             @OA\Property(property="classification_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function classify(Request $request)
    {
        try {
            // Xác thực dữ liệu đầu vào
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'result' => 'required|string',
                'llm_response' => 'required|string', // Thêm xác thực cho llm_response
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

            // Lấy kết quả nhận diện và llm_response từ request
            $result = $request->input('result');
            $llmResponse = $request->input('llm_response');

            // Lưu vào bảng classifications
            $classification = \App\Models\Classification::create([
                'user_id' => $userId,
                'image_path' => '/storage/' . $imagePath,
                'result' => $result,
                'llm_response' => $llmResponse, // Lưu llm_response
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

    /**
     * @OA\Get(
     *     path="/dashboard",
     *     tags={"Ceramic"},
     *     summary="Get user dashboard",
     *     description="Retrieve classification history for the authenticated user",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="classifications", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function dashboard()
    {
        // Lấy người dùng đã đăng nhập
        $user = auth()->user();

        // Truy xuất lịch sử nhận diện với phân trang
        $classifications = \App\Models\Classification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Truyền dữ liệu vào view
        return view('dashboard', compact('classifications'));
    }

    /**
     * @OA\Get(
     *     path="/classification/{id}",
     *     tags={"Ceramic"},
     *     summary="Get classification info",
     *     description="Retrieve LLM response for a specific classification",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", description="Classification ID")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="llm_response", type="string")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Classification not found")
     * )
     */
    public function getClassificationInfo($id)
    {
        $classification = \App\Models\Classification::findOrFail($id);
        if ($classification->user_id !== auth()->id()) {
            return response()->json(['error' => 'Không có quyền truy cập'], 403);
        }
        return response()->json(['llm_response' => $classification->llm_response]);
    }

    /**
     * @OA\Get(
     *     path="/classification/history",
     *     tags={"Ceramic"},
     *     summary="Get classification history",
     *     description="Retrieve the classification history for the authenticated user",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="redirect_url", type="string"),
     *             @OA\Property(
     *                 property="history",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="image_path", type="string"),
     *                     @OA\Property(property="result", type="string"),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="llm_response", type="string")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function getHistory(Request $request)
    {
        try {
            $user = auth('api')->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Người dùng chưa đăng nhập.'
                ], 401);
            }

            $classifications = \App\Models\Classification::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'redirect_url' => 'your-app://redirect-to-somewhere', // Chuyển hướng URL
                'history' => $classifications->map(function ($classification) {
                    return [
                        'id' => $classification->id,
                        'image_path' => $classification->image_path,
                        'result' => $classification->result,
                        'created_at' => $classification->created_at->format('Y-m-d H:i:s'),
                        'llm_response' => $classification->llm_response
                    ];
                })->toArray()
            ], 200);
        } catch (\Exception $e) {
            \Log::error('Error fetching classification history: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy lịch sử: ' . $e->getMessage()
            ], 500);
        }
    }
}