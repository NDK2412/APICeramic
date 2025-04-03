<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RechargeRequest;
use App\Models\RechargeHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Message;         // Thêm import cho Message


class RechargeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $rechargeHistory = RechargeHistory::where('user_id', Auth::id())->get();
        $requests = RechargeRequest::where('user_id', auth()->id())->get();
        $messages = Message::where('user_id', auth()->id())->orderBy('created_at')->get();
        

        return view('recharge', compact('requests', 'messages', 'rechargeHistory'));
     
    }
    // public function sendMessage(Request $request)
    // {
    //     $request->validate(['message' => 'required|string|max:500']);

    //     Message::create([
    //         'user_id' => auth()->id(),
    //         'admin_id' => null,
    //         'message' => $request->message,
    //     ]);

    //     return redirect()->back()->with('success', 'Đã gửi tin nhắn cho admin.');
    // }
    public function submit(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|in:50000,100000,200000',
            'proof_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $amount = $request->amount;
        $tokens = $this->calculateTokens($amount);

        // Lưu ảnh vào public/storage/proof_images
        $fileName = $request->file('proof_image')->hashName();
        $request->file('proof_image')->move(public_path('storage/proof_images'), $fileName);
        $proofPath = 'proof_images/' . $fileName;

        // Lưu vào database
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




    // Trong RechargeController.php
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
}