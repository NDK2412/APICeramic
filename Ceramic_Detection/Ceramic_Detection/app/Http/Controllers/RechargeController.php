<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RechargeRequest;
use App\Models\RechargeHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Message;

class RechargeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $rechargeHistory = RechargeHistory::where('user_id', Auth::id())->get();
        $requests = RechargeRequest::where('user_id', Auth::id())->get();
        $messages = Message::where('user_id', Auth::id())->orderBy('created_at')->get();

        // Tính toán các thống kê
        $pendingRequestsCount = $requests->where('status', 'pending')->count(); // Số yêu cầu đang chờ duyệt
        $approvedRequestsCount = $requests->where('status', 'approved')->count(); // Số yêu cầu đã duyệt
        $totalAmount = $rechargeHistory->sum('amount'); // Tổng số tiền đã nạp
        $totalTokens = $rechargeHistory->sum('tokens_added'); // Tổng số token đã nạp

        return view('recharge', compact(
            'requests',
            'messages',
            'rechargeHistory',
            'pendingRequestsCount',
            'approvedRequestsCount',
            'totalAmount',
            'totalTokens'
        ));
    }

    // Các phương thức khác giữ nguyên
    public function submit(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|in:50000,100000,200000',
            'proof_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $amount = $request->amount;
        $tokens = $this->calculateTokens($amount);

        $fileName = $request->file('proof_image')->hashName();
        $request->file('proof_image')->move(public_path('storage/proof_images'), $fileName);
        $proofPath = 'proof_images/' . $fileName;

        RechargeRequest::create([
            'user_id' => Auth::id(),
            'amount' => $amount,
            'requested_tokens' => $tokens,
            'proof_image' => $proofPath,
        ]);

        return redirect()->route('recharge.index')->with('success', 'Yêu cầu nạp tiền đã được gửi, chờ admin xác nhận!');
    }

    private function calculateTokens($amount)
    {
        switch ($amount) {
            case 50000:
                return 50;
            case 100000:
                return 110;
            case 200000:
                return 240;
            default:
                return 0;
        }
    }

    public function exportReceipt($id)
    {
        $record = RechargeHistory::findOrFail($id);
        $request = RechargeRequest::where('user_id', $record->user_id)
            ->where('amount', $record->amount)
            ->where('requested_tokens', $record->tokens_added)
            ->first();

        $proofImagePath = $request ? public_path('storage/' . $request->proof_image) : null;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('receipt', [
            'record' => $record,
            'proof_image' => $proofImagePath,
        ]);

        return $pdf->download('HoaDon_NapTien_' . $id . '.pdf');
    }

    public function verify(Request $request)
    {
        $password = $request->input('password');
        $user = Auth::user();

        if (!Hash::check($password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Mật khẩu không chính xác!'
            ], 401);
        }

        $recaptchaResponse = $request->input('g-recaptcha-response');
        $client = new Client();
        $response = $client->post('https://www.google.com/recaptcha/api/siteverify', [
            'form_params' => [
                'secret' => env('RECAPTCHA_SECRET_KEY'),
                'response' => $recaptchaResponse,
                'remoteip' => $request->ip()
            ]
        ]);

        $recaptchaData = json_decode($response->getBody(), true);

        if (!$recaptchaData['success']) {
            return response()->json([
                'success' => false,
                'message' => 'Xác nhận CAPTCHA không thành công!'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Xác nhận thành công!'
        ]);
    }
}