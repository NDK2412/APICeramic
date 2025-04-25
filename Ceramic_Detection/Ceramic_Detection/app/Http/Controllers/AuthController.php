<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Setting;
use App\Models\LoginHistory;
use App\Models\Apk;

/**
 *   @OA\Info(
 *     title="Auth",
 *     version="1.0.0", 
 *   )
 * )
 */

class AuthController extends Controller
{
    /**
     * @OA\Get(
     *     path="/",
     *     tags={"Auth"},
     *     summary="Get home page",
     *     description="Retrieve the home page with the latest APK information",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="latestApk", type="object", description="Latest APK details")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $latestApk = Apk::latest()->first();
        return view('index', compact('latestApk'));
    }

    /**
     * @OA\Get(
     *     path="/login",
     *     tags={"Auth"},
     *     summary="Show login form",
     *     description="Retrieve the login form with reCAPTCHA status",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="recaptchaEnabled", type="boolean", description="reCAPTCHA enabled status")
     *         )
     *     )
     * )
     */
    public function showLoginForm()
    {
        $recaptchaEnabled = Setting::where('key', 'recaptcha_enabled')->first();
        $recaptchaEnabled = $recaptchaEnabled ? ($recaptchaEnabled->recaptcha_enabled == '1') : false;
        \Log::info("recaptchaEnabled in showLoginForm: " . ($recaptchaEnabled ? 'true' : 'false'));

        return view('login', compact('recaptchaEnabled'));
    }

    /**
     * @OA\Post(
     *     path="/login",
     *     tags={"Auth"},
     *     summary="User login",
     *     description="Authenticate a user with email, password, and optional reCAPTCHA",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", format="email", description="User's email"),
     *             @OA\Property(property="password", type="string", description="User's password"),
     *             @OA\Property(property="g-recaptcha-response", type="string", description="reCAPTCHA response token")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="redirect", type="string", description="Redirect URL")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Invalid credentials"),
     *     @OA\Response(response=403, description="Account locked")
     * )
     */
    public function login(Request $request)
    {
        $recaptchaEnabled = Setting::where('key', 'recaptcha_enabled')->first();
        $recaptchaEnabled = $recaptchaEnabled ? ($recaptchaEnabled->value == '1') : false;
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $credentials['email'])->first();
        if ($recaptchaEnabled && empty($request->input('g-recaptcha-response'))) {
            return back()->withErrors([
                'g-recaptcha-response' => 'Vui lòng tích vào CAPTCHA.',
            ])->onlyInput('email');
        }

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

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

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

    /**
     * @OA\Get(
     *     path="/register",
     *     tags={"Auth"},
     *     summary="Show registration form",
     *     description="Retrieve the registration form",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object")
     *     )
     * )
     */
    public function showRegisterForm()
    {
        return view('register');
    }

    /**
     * @OA\Post(
     *     path="/register",
     *     tags={"Auth"},
     *     summary="User registration",
     *     description="Register a new user with provided details",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", description="User's name"),
     *             @OA\Property(property="email", type="string", format="email", description="User's email"),
     *             @OA\Property(property="password", type="string", description="User's password"),
     *             @OA\Property(property="password_confirmation", type="string", description="Password confirmation"),
     *             @OA\Property(property="phone", type="string", description="User's phone number", nullable=true),
     *             @OA\Property(property="address", type="string", description="User's address", nullable=true),
     *             @OA\Property(property="id_number", type="string", description="User's ID number", nullable=true),
     *             @OA\Property(property="passport", type="string", description="User's passport number", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful registration",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="Success message")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'id_number' => 'nullable|string|max:20',
            'passport' => 'nullable|string|max:20',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'id_number' => $request->id_number,
            'passport' => $request->passport,
            'tokens' => 3,
            'status' => 'active',
        ]);

        return redirect()->route('login')->with('success', 'Tài khoản đã được tạo! Vui lòng đăng nhập.');
    }

    /**
     * @OA\Post(
     *     path="/logout",
     *     tags={"Auth"},
     *     summary="User logout",
     *     description="Log out the authenticated user and invalidate the session",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful logout",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="redirect", type="string", description="Redirect URL")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    /**
     * @OA\Post(
     *     path="/use-token",
     *     tags={"Auth"},
     *     summary="Use a token",
     *     description="Deduct one token from the authenticated user's balance",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="tokens", type="integer", description="Remaining tokens")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Insufficient tokens")
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/change-name",
     *     tags={"Auth"},
     *     summary="Change user name",
     *     description="Update the authenticated user's name",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="userId", type="integer", description="User ID"),
     *             @OA\Property(property="name", type="string", description="New name")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Auth"},
     *     summary="API user login",
     *     description="Authenticate a user via API and return an API token",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", format="email", description="User's email"),
     *             @OA\Property(property="password", type="string", description="User's password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="token", type="string", description="API token"),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="phone", type="string", nullable=true),
     *                 @OA\Property(property="address", type="string", nullable=true),
     *                 @OA\Property(property="id_number", type="string", nullable=true),
     *                 @OA\Property(property="passport", type="string", nullable=true),
     *                 @OA\Property(property="tokens", type="integer"),
     *                 @OA\Property(property="role", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=400, description="Invalid request"),
     *     @OA\Response(response=401, description="Invalid credentials"),
     *     @OA\Response(response=403, description="Account locked"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
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
                
                LoginHistory::create([
                    'user_id' => $user->id,
                    'login_time' => now(),
                    'ip_address' => $request->ip(),
                    'device_info' => $request->header('User-Agent') ?? 'Unknown'
                ]);
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
                        'phone' => $user->phone,
                        'address' => $user->address,
                        'id_number' => $user->id_number,
                        'passport' => $user->passport,
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

    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"Auth"},
     *     summary="API user registration",
     *     description="Register a new user via API and return an API token",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", description="User's name"),
     *             @OA\Property(property="email", type="string", format="email", description="User's email"),
     *             @OA\Property(property="password", type="string", description="User's password"),
     *             @OA\Property(property="password_confirmation", type="string", description="Password confirmation"),
     *             @OA\Property(property="phone", type="string", description="User's phone number", nullable=true),
     *             @OA\Property(property="address", type="string", description="User's address", nullable=true),
     *             @OA\Property(property="id_number", type="string", description="User's ID number", nullable=true),
     *             @OA\Property(property="passport", type="string", description="User's passport number", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful registration",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="token", type="string", description="API token"),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="phone", type="string", nullable=true),
     *                 @OA\Property(property="address", type="string", nullable=true),
     *                 @OA\Property(property="id_number", type="string", nullable=true),
     *                 @OA\Property(property="passport", type="string", nullable=true),
     *                 @OA\Property(property="tokens", type="integer"),
     *                 @OA\Property(property="role", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function apiRegister(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6|confirmed',
                'phone' => 'nullable|string|max:15',
                'address' => 'nullable|string|max:255',
                'id_number' => 'nullable|string|max:20',
                'passport' => 'nullable|string|max:20',
            ]);

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'phone' => $data['phone'],
                'address' => $data['address'],
                'id_number' => $data['id_number'],
                'passport' => $data['passport'],
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
                    'phone' => $user->phone,
                    'address' => $user->address,
                    'id_number' => $user->id_number,
                    'passport' => $user->passport,
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

    /**
     * @OA\Get(
     *     path="/api/user",
     *     tags={"Auth"},
     *     summary="Get authenticated user",
     *     description="Retrieve details of the authenticated user using API token",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="phone", type="string", nullable=true),
     *                 @OA\Property(property="address", type="string", nullable=true),
     *                 @OA\Property(property="id_number", type="string", nullable=true),
     *                 @OA\Property(property="passport", type="string", nullable=true),
     *                 @OA\Property(property="tokens", type="integer"),
     *                 @OA\Property(property="role", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Invalid token"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
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
                        'phone' => $user->phone,
                        'address' => $user->address,
                        'id_number' => $user->id_number,
                        'passport' => $user->passport,
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

    /**
     * @OA\Get(
     *     path="/auth/google/callback",
     *     tags={"Auth"},
     *     summary="Handle Google OAuth callback",
     *     description="Process Google OAuth callback and return an API token",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="redirect", type="string", description="Redirect URL with token")
     *         )
     *     ),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            $user = User::where('email', $googleUser->email)->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'password' => Hash::make(Str::random(16)),
                    'tokens' => 3,
                    'status' => 'active',
                    'role' => 'user',
                    'api_token' => bin2hex(random_bytes(32)),
                ]);
            } else {
                $user->api_token = bin2hex(random_bytes(32));
                $user->save();
            }

            Log::info('Google login successful', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            return redirect()->away("ceramicprediction://auth?token={$user->api_token}");
        } catch (\Exception $e) {
            Log::error('handleGoogleCallback error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi đăng nhập Google: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/auth/google/redirect",
     *     tags={"Auth"},
     *     summary="Redirect to Google OAuth",
     *     description="Redirect the user to Google OAuth login page",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="success", type="boolean"),
     *             @OA\Property(property="url", type="string", description="Google OAuth redirect URL")
     *         )
     *     ),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function redirectToGoogle()
    {
        try {
            $url = Socialite::driver('google')->stateless()->redirect()->getTargetUrl();
            return response()->json([
                'success' => true,
                'url' => $url,
            ], 200);
        } catch (\Exception $e) {
            Log::error('redirectToGoogle error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Lỗi chuyển hướng Google: ' . $e->getMessage(),
            ], 500);
        }
    }
}