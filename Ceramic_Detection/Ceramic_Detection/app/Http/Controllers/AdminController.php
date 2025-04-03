<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use App\Models\Recharge;
use App\Models\RechargeRequest;
use App\Models\RechargeHistory;
use App\Models\Ceramic;
class AdminController extends Controller
{
    public function __construct()
    {
        // Đăng ký middleware trong constructor
        $this->middleware('auth'); // Middleware kiểm tra đăng nhập
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'admin') {
                return redirect('/dashboard');
            }
            return $next($request);
        });
    }
    public function rejectRecharge(Request $request)
    {
        $request->validate([
            'request_id' => 'required|exists:recharge_requests,id',
            'reason' => 'required|string|max:500',
        ]);

        $rechargeRequest = RechargeRequest::findOrFail($request->request_id);
        $rechargeRequest->status = 'rejected';
        $rechargeRequest->save();

        // Lưu tin nhắn cho người dùng
        Message::create([
            'user_id' => $rechargeRequest->user_id,
            'admin_id' => auth()->id(),
            'message' => "Yêu cầu nạp tiền của bạn (ID: {$rechargeRequest->id}) đã bị từ chối. Lý do: " . $request->reason,
            'created_at' => now(),
        ]);

        return redirect()->route('admin.index')->with('success', 'Đã từ chối yêu cầu và gửi tin nhắn cho người dùng.');
    }

    public function index()
    {
        $users = User::all();
        $rechargeRequests = RechargeRequest::where('status', 'pending')->get();
        $totalRevenue = RechargeHistory::sum('amount');
        $averageRating = User::avg('rating') ?? 0;

        $revenueData = RechargeHistory::select(
            DB::raw('DATE_FORMAT(approved_at, "%Y-%m") as month'),
            DB::raw('SUM(amount) as total')
        )
        ->whereNotNull('approved_at')
        ->groupBy('month')
        ->orderBy('month')
        ->get();



        // Lấy lịch sử giao dịch (tất cả các yêu cầu nạp tiền)
        $transactionHistory = RechargeRequest::with('user')
            ->orderBy('created_at', 'desc')
            ->take(10) // Giới hạn 10 giao dịch gần nhất để tránh quá tải
            ->get();
        // Tính tổng doanh thu
        $totalRevenue = RechargeRequest::where('status', 'approved')->sum('amount');
     
        $revenueLabels = $revenueData->pluck('month')->toArray();
        $revenueData = $revenueData->pluck('total')->toArray();

        if (empty($revenueLabels)) {
            $revenueLabels = ['Chưa có dữ liệu'];
            $revenueData = [0];
        }

        // Lấy danh sách người dùng đã gửi tin nhắn
        $chatUsers = User::whereHas('messages', function ($query) {
            $query->whereNotNull('message');
        })->get();

        //dữ liệu từ bảng ceramics
        $ceramics = Ceramic::all();
        return view('admin', compact('users', 'rechargeRequests', 'totalRevenue', 'averageRating', 'revenueLabels', 'revenueData', 'chatUsers','transactionHistory','ceramics'));
    }
    public function sendChatMessage(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string|max:500',
        ]);

        Message::create([
            'user_id' => $request->user_id,
            'admin_id' => auth()->id(),
            'message' => $request->message,
        ]);

        return redirect()->back()->with('success', 'Đã gửi tin nhắn.');
    }
    public function getChat($userId)
    {
        $messages = Message::where('user_id', $userId)->orderBy('created_at')->get();
        return response()->json(['messages' => $messages]);
    }    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // Xác thực dữ liệu
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:user,admin',
            'tokens' => 'required|integer|min:0',
        ]);

        // Cập nhật dữ liệu
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'role' => $request->input('role'),
            'tokens' => $request->input('tokens'),
        ]);

        return redirect()->route('admin.index')->with('success', 'Cập nhật người dùng thành công!');
    }
    
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->back()->with('success', 'Người dùng đã được xóa thành công.');
    }


    public function approveRecharge($id)
    {
        $request = RechargeRequest::findOrFail($id);
        if ($request->status !== 'pending') {
            return redirect()->route('admin.index')->with('error', 'Yêu cầu này đã được xử lý!');
        }

        $user = User::findOrFail($request->user_id);
        $user->tokens += $request->requested_tokens;
        $user->save();

        RechargeHistory::create([
            'user_id' => $request->user_id,
            'amount' => $request->amount,
            'tokens_added' => $request->requested_tokens,
            'approved_at' => now(),
        ]);

        $request->update(['status' => 'approved']);

        return redirect()->route('admin.index')->with('success', 'Yêu cầu nạp tiền đã được xác nhận!');
    }

    



    // Lưu món đồ gốm mới
public function storeCeramic(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'image' => 'nullable|string|max:255',
        'category' => 'nullable|string|max:255',
        'origin' => 'nullable|string|max:255',
    ]);

    Ceramic::create($validated);

    return redirect()->route('admin.index')->with('success', 'Thêm món đồ gốm thành công!');
}

// Cập nhật thông tin món đồ gốm
public function updateCeramic(Request $request, $id)
{
    $ceramic = Ceramic::findOrFail($id);

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'image' => 'nullable|string|max:255',
        'category' => 'nullable|string|max:255',
        'origin' => 'nullable|string|max:255',
    ]);

    $ceramic->update($validated);

    return redirect()->route('admin.index')->with('success', 'Cập nhật món đồ gốm thành công!');
}

// Xóa món đồ gốm
public function deleteCeramic($id)
{
    $ceramic = Ceramic::findOrFail($id);
    $ceramic->delete();

    return redirect()->route('admin.index')->with('success', 'Xóa món đồ gốm thành công!');
}
}