<?php

use App\Http\Controllers\PredictionController;
use App\Http\Controllers\ImagePredictionController;

use App\Http\Controllers\CeramicController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;


use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\ImageController;


Route::post('/predict', [PredictionController::class, 'predict']);

Route::get('/check-auth', function () {
    return response()->json(['authenticated' => Auth::check()]);
});
Route::post('/logout', function () {
    Auth::logout();
    return response()->json(['message' => 'Logged out successfully']);
});
Route::middleware('auth:sanctum')->get('/check-auth', function (Request $request) {
    return response()->json(['authenticated' => true, 'user' => $request->user()]);
});


// Hiển thị trang login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Hiển thị trang đăng ký
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');

Route::get('/', [CeramicController::class, 'index'])->name('home');

Route::post('/predict', [ImagePredictionController::class, 'predict'])->name('predict');
    

Route::get('/', function () {
    return view('index'); // Hoặc bất kỳ view nào có sẵn


});

/*use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/upload', function () {
    return view('upload');
});

Route::post('/upload', function (Request $request) {
    // Validate input
    $request->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Lưu ảnh vào thư mục public/images
    $imageName = time() . '.' . $request->image->extension();
    $request->image->move(public_path('images'), $imageName);

    return back()->with('success', 'Ảnh đã được tải lên thành công!')->with('image', $imageName);
});
*/