<?php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PredictionController;
use App\Http\Controllers\ImagePredictionController;

use App\Http\Controllers\CeramicController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RechargeController;

use Illuminate\Support\Facades\Auth;

// use App\Http\Controllers\ImageController;
// use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\CustomForgotPasswordController;


use App\Http\Controllers\NewsController;
use App\Http\Controllers\Auth\LoginController;



Route::get('/gallery', [CeramicController::class, 'gallery'])->name('gallery');
Route::get('/ceramics/{id}', [CeramicController::class, 'show'])->name('ceramics.show');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
//Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/dashboard', function () {
    return view('ceramic.index');
})->middleware('auth')->name('dashboard');
Route::get('/', [NewsController::class, 'index'])->name('index');

Route::get('/news', [NewsController::class, 'news'])->name('news');

// Hiển thị form quên mật khẩu
Route::get('/forgot-password', [CustomForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');

// Xử lý đặt lại mật khẩu trực tiếp
Route::post('/reset-password', [CustomForgotPasswordController::class, 'resetPassword'])->name('password.update');


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

Route::post('/use-token', [AuthController::class, 'useToken'])->middleware('auth'); // Route giảm token
Route::get('/recharge', function () {
    return view('recharge'); // Trang nạp token (chưa triển khai)
})->middleware('auth')->name('recharge');



Route::get('/admin', function () {
    return view('admin');
})->middleware('auth')->name('admin');





Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::put('/admin/users/{id}', [AdminController::class, 'update'])->name('admin.update');
    
    Route::delete('/admin/users/{id}', [AdminController::class, 'destroy'])->name('admin.delete');
    Route::post('/admin/recharge/approve/{id}', [AdminController::class, 'approveRecharge'])->name('admin.recharge.approve');
    Route::post('/admin/recharge/reject', [AdminController::class, 'rejectRecharge'])->name('admin.recharge.reject');
});
// Route cho admin
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/edit/{id}', [AdminController::class, 'edit'])->name('admin.edit');
    Route::put('/update/{id}', [AdminController::class, 'update'])->name('admin.update');
    Route::delete('/delete/{id}', [AdminController::class, 'destroy'])->name('admin.delete');
});
//Xử lý đánh giá người dùng

Route::post('/submit-rating', [App\Http\Controllers\UserController::class, 'submitRating'])->name('submit.rating');

Route::get('/recharge', [App\Http\Controllers\RechargeController::class, 'index'])->name('recharge.index');
Route::post('/recharge', [App\Http\Controllers\RechargeController::class, 'submit'])->name('recharge.submit');
Route::post('/admin/recharge/{id}/approve', [App\Http\Controllers\AdminController::class, 'approveRecharge'])->name('admin.recharge.approve');



Route::get('/recharge/export/{id}', [RechargeController::class, 'exportReceipt'])->name('recharge.export');


Route::post('/recharge/message', [RechargeController::class, 'sendMessage'])->name('recharge.message');
Route::post('/admin/recharge/reject', [AdminController::class, 'rejectRecharge'])->name('admin.recharge.reject');

//Route setting
Route::post('/admin/settings/timezone', [App\Http\Controllers\AdminController::class, 'updateTimezone'])->name('admin.settings.timezone');


// Routes cho quản lý thư viện đồ gốm
Route::post('/admin/ceramics', [AdminController::class, 'storeCeramic'])->name('admin.ceramics.store');
Route::put('/admin/ceramics/{id}', [AdminController::class, 'updateCeramic'])->name('admin.ceramics.update');
Route::delete('/admin/ceramics/{id}', [AdminController::class, 'deleteCeramic'])->name('admin.ceramics.delete');



Route::post('/classify', [App\Http\Controllers\CeramicController::class, 'classify'])->name('classify');
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




Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
Route::put('/admin/users/{id}', [AdminController::class, 'update'])->name('admin.update');
Route::delete('/admin/users/{id}', [AdminController::class, 'delete'])->name('admin.delete');