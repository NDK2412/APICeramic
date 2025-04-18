<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Setting;

class AuthController extends Controller
{
    public function index()
    {
        return view('index'); // Đảm bảo file index.blade.php tồn tại trong thư mục resources/views
    }
    // Hiển thị form đăng nhập
    public function showLoginForm()
    {
        $recaptchaEnabled = Setting::where('key', 'recaptcha_enabled')->first();
        $recaptchaEnabled = $recaptchaEnabled ? ($recaptchaEnabled->recaptcha_enabled == '1') : false;
        \Log::info("recaptchaEnabled in showLoginForm: " . ($recaptchaEnabled ? 'true' : 'false'));

        return view('login', compact('recaptchaEnabled'));
    }

    // Xử lý đăng nhập
    public function login(Request $request)
    {
        
        $recaptchaEnabled = Setting::where('key', 'recaptcha_enabled')->first();
        $recaptchaEnabled = $recaptchaEnabled ? ($recaptchaEnabled->value == '1') : false;
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Tìm người dùng dựa trên email trước khi xác thực
        $user = User::where('email', $credentials['email'])->first();
        if ($recaptchaEnabled && empty($request->input('g-recaptcha-response'))) {
            return back()->withErrors([
                'g-recaptcha-response' => 'Vui lòng tích vào CAPTCHA.',
            ])->onlyInput('email');
        }
        // Nếu tài khoản không tồn tại, trả về lỗi
        if (!$user) {
            return back()->withErrors([
                'email' => 'Thông tin đăng nhập không chính xác.',
            ])->onlyInput('email');
        }

        // Kiểm tra trạng thái tài khoản
        if (!$user->isActive()) {
            return back()->withErrors([
                'email' => 'Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên.',
            ])->onlyInput('email');
        }

        // Nếu tài khoản hoạt động, tiến hành xác thực
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
            'status' => 'active', // Đảm bảo tài khoản mới tạo có status là active
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
    //Đổi tên
    public function changeName(Request $request)
    {
        $request->validate([
            'userId' => 'required',
            'name' => 'required|string|min:3|max:255',
        ]);

        $user = User::find($request->userId);

        if (!$user || $user->id !== Auth::id()) {
            return response()->json(['error' => 'Không có quyền thay đổi tên!'], 403);
        }

        $user->name = $request->name;
        $user->save();

        return response()->json(['success' => true]);
    }
    // API login for Android (using api_token column)
    public function apiLogin(Request $request)
    {

        try {
            if (!$request->isJson()) {
                Log::warning('Login attempt with non-JSON content', [
                    'headers' => $request->headers->all(),
                    'body' => $request->all(),
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Yêu cầu phải có Content-Type: application/json'
                ], 400);
            }

            $credentials = $request->only('email', 'password');

            Log::info('Login attempt', ['email' => $credentials['email']]);

            if (empty($credentials['email']) || empty($credentials['password'])) {
                Log::warning('Missing credentials', ['body' => $credentials]);
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng cung cấp email và mật khẩu'
                ], 400);
            }

            $user = User::where('email', $credentials['email'])->first();

            if ($user && Hash::check($credentials['password'], $user->password)) {
                if ($user->status !== 'active') {
                    Log::warning('Inactive user login attempt', [
                        'user_id' => $user->id,
                        'email' => $user->email,
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Tài khoản của bạn đã bị khóa.'
                    ], 403);
                }

                $token = Str::random(60);
                $user->api_token = $token;
                $user->save();

                Log::info('Login successful', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'token' => substr($token, 0, 10) . '...',
                ]);

                return response()->json([
                    'success' => true,
                    'token' => $token,
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'tokens' => $user->tokens,
                        'role' => $user->role
                    ]
                ], 200);
            }

            Log::warning('Invalid login credentials', ['email' => $credentials['email']]);
            return response()->json([
                'success' => false,
                'message' => 'Thông tin đăng nhập không đúng.'
            ], 401);
        } catch (\Exception $e) {
            Log::error('Login error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Lỗi server khi đăng nhập: ' . $e->getMessage()
            ], 500);
        }
    }
    //Vẫn là phần dành cho android
    public function apiRegister(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6|confirmed',
            ]);

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'tokens' => 3,
                'status' => 'active',
                'role' => 'user',
                'api_token' => bin2hex(random_bytes(32)),
            ]);

            return response()->json([
                'success' => true,
                'token' => $user->api_token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'tokens' => $user->tokens,
                    'role' => $user->role,
                ]
            ], 201);
        } catch (\Exception $e) {
            \Log::error('apiRegister error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi đăng ký: ' . $e->getMessage()
            ], 500);
        }
    }
    //Lấy thông tn người dùng
    public function getUser(Request $request)
    {
        try {
            $apiToken = $request->bearerToken();
            if (!$apiToken) {
                Log::warning('No token provided for getUser');
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng cung cấp api_token.'
                ], 401);
            }

            $user = User::where('api_token', $apiToken)->first();
            if ($user) {
                return response()->json([
                    'success' => true,
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'tokens' => $user->tokens,
                        'role' => $user->role
                    ]
                ], 200);
            }

            Log::warning('Invalid token for getUser', [
                'token' => substr($apiToken, 0, 10) . '...',
            ]);
            return response()->json([
                'success' => false,
                'message' => 'api_token không hợp lệ.'
            ], 401);
        } catch (\Exception $e) {
            Log::error('Get user error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy thông tin người dùng: ' . $e->getMessage()
            ], 500);
        }
    }
}