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
use App\Models\RechargePackage;
use Illuminate\Support\Facades\Log;

class RechargeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $packages = RechargePackage::where('is_active', true)->get();
        $rechargeHistory = RechargeHistory::where('user_id', Auth::id())
            ->orderBy('approved_at', 'desc')
            ->get();
        $requests = RechargeRequest::where('user_id', Auth::id())->get();
        $messages = Message::where('user_id', Auth::id())->orderBy('created_at')->get();

        $pendingRequestsCount = $requests->where('status', 'pending')->count();
        $approvedRequestsCount = $requests->where('status', 'approved')->count();
        $totalAmount = $rechargeHistory->sum('amount');
        $totalTokens = $rechargeHistory->sum('tokens_added');

        return view('recharge', compact(
            'requests',
            'messages',
            'rechargeHistory',
            'pendingRequestsCount',
            'approvedRequestsCount',
            'totalAmount',
            'totalTokens',
            'packages'
        ));
    }

    public function submit(Request $request)
    {
        try {
            $package = RechargePackage::findOrFail($request->package_id);
            $amount = $package->amount;
            $tokens = $package->tokens;

            $request->validate([
                'package_id' => 'required|exists:recharge_packages,id',
                'proof_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            // Kiểm tra file hợp lệ
            $file = $request->file('proof_image');
            if (!$file->isValid()) {
                Log::error('File ảnh không hợp lệ: ' . $file->getClientOriginalName());
                return redirect()->back()->with('error', 'File ảnh không hợp lệ, vui lòng thử lại!');
            }

            // Tạo tên file duy nhất
            $fileName = 'proof_' . time() . '_' . $file->hashName();
            $proofPath = 'proof_images/' . $fileName;

            // Lưu trực tiếp vào proof_images
            $fileContent = file_get_contents($file->getRealPath());
            Storage::disk('public')->put($proofPath, $fileContent);
            Log::info('Ảnh chứng minh được lưu tại: ' . $proofPath);

            // Kiểm tra file đã lưu thành công
            if (!Storage::disk('public')->exists($proofPath)) {
                Log::error('Lỗi khi lưu file ảnh: ' . $proofPath);
                return redirect()->back()->with('error', 'Lỗi khi lưu ảnh chứng minh, vui lòng thử lại!');
            }

            RechargeRequest::create([
                'user_id' => Auth::id(),
                'amount' => $amount,
                'requested_tokens' => $tokens,
                'proof_image' => $proofPath,
            ]);

            return redirect()->route('recharge.index')->with('success', 'Yêu cầu nạp tiền đã được gửi, chờ admin xác nhận!');
        } catch (\Exception $e) {
            Log::error('Lỗi khi xử lý upload ảnh: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Lỗi khi gửi yêu cầu nạp tiền: ' . $e->getMessage());
        }
    }

    public function exportReceipt($id)
    {
        try {
            $record = RechargeHistory::findOrFail($id);
            $proofImagePath = $record->proof_image ? public_path('storage/' . $record->proof_image) : null;
            // Log để debug
            Log::info('exportReceipt: proof_image từ database: ' . $record->proof_image);
            Log::info('exportReceipt: proofImagePath: ' . $proofImagePath);

            // Tạm bỏ kiểm tra exists để test hiển thị ảnh
            /*
            if ($proofImagePath && !Storage::disk('public')->exists($record->proof_image)) {
                Log::warning('Ảnh chứng minh không tồn tại tại: ' . $record->proof_image);
                $proofImagePath = null;
            }
            */

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('receipt', [
                'record' => $record,
                'proof_image' => $proofImagePath,
            ]);

            return $pdf->download('HoaDon_NapTien_' . $id . '.pdf');
        } catch (\Exception $e) {
            Log::error('Lỗi khi xuất PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Lỗi khi xuất hóa đơn: ' . $e->getMessage());
        }
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