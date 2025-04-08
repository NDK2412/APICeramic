<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;
use App\Models\User;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        $recaptchaEnabled = \App\Models\Setting::where('key', 'recaptcha_enabled')->first();
        $recaptchaEnabled = $recaptchaEnabled ? ($recaptchaEnabled->value == '1') : false;

        return view('login', compact('recaptchaEnabled'));
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        // Tìm người dùng dựa trên email trước khi xác thực
        $user = User::where('email', $credentials['email'])->first();

        // Nếu tài khoản không tồn tại hoặc không hoạt động, trả về lỗi ngay lập tức
        if (!$user) {
            return back()->withErrors([
                'email' => 'Thông tin đăng nhập không chính xác.',
            ])->onlyInput('email');
        }

        if (!$user->isActive()) {
            return back()->withErrors([
                'email' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.',
            ])->onlyInput('email');
        }

        // Nếu tài khoản hoạt động, tiến hành xác thực
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        // Nếu xác thực thất bại (mật khẩu sai, v.v.)
        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác.',
        ])->onlyInput('email');
    }

    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request),
            $request->filled('remember')
        );
    }
}