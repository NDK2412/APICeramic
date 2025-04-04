<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Setting;
class AuthController extends Controller
{
    // Hiển thị form đăng nhập
    public function showLoginForm()
    {
        // Lấy trạng thái CAPTCHA từ bảng settings
        $recaptchaEnabled = Setting::where('key', 'recaptcha_enabled')->first();
        $recaptchaEnabled = $recaptchaEnabled ? ($recaptchaEnabled->recaptcha_enabled == '1') : false;
        \Log::info("recaptchaEnabled in showLoginForm: " . ($recaptchaEnabled ? 'true' : 'false'));

        return view('login', compact('recaptchaEnabled'));
    }

    // Xử lý đăng nhập
    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        // Chuyển hướng dựa trên role
        if (Auth::user()->role === 'admin') {
            return redirect()->intended('/admin');
        } else {
            return redirect()->intended('/dashboard');
        }
    }

    return back()->withErrors([
        'email' => 'Thông tin đăng nhập không chính xác.',
    ])->onlyInput('email');
}

    // Hiển thị form đăng ký
    public function showRegisterForm()
    {
        return view('register');
    }

    // Xử lý đăng ký
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tokens' => 3, 
        ]);

        return redirect()->route('login')->with('success', 'Tài khoản đã được tạo! Vui lòng đăng nhập.');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/'); // Chuyển hướng về http://localhost:8000/
    }


    public function useToken(Request $request)
    {
        $user = Auth::user();
        if ($user->tokens > 0) {
            $user->tokens -= 1;
            $user->tokens_used += 1;
            $user->save();
            return response()->json(['success' => true, 'tokens' => $user->tokens]);
        }
        return response()->json(['success' => false, 'message' => 'Hết lượt dự đoán']);
    }
}
