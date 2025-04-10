<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Spatie\DbDumper\Databases\MySql;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Recharge;
use App\Models\RechargeRequest;
use App\Models\RechargeHistory;
use App\Models\TermsAndConditions;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\Ceramic;
use App\Models\News;
use App\Models\TokenUsage;
use App\Models\Setting;
use App\Models\Classification;
use Illuminate\Support\Facades\Cache;
use App\Jobs\FetchLaravelStats;
use App\Jobs\FetchFastApiStats;
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
            'message' => "Yêu cầu nạp tiền của bạn (Ngày: {$rechargeRequest->updated_at}) đã bị từ chối. Lý do: " . $request->reason,
            'created_at' => now(),
        ]);
        return redirect()->route('admin.index')->with('success', 'Đã từ chối yêu cầu và gửi tin nhắn cho người dùng.');
    }
    public function index()
    {
        $users = User::all();
        //Liên quan bật tăt thôngt tin hệ thống
        $optimizationSetting = Setting::where('key', 'system_info_optimization')->first();
        $isSystemInfoEnabled = $optimizationSetting && $optimizationSetting->value === 'enabled';
        if ($isSystemInfoEnabled) {
            FetchLaravelStats::dispatch();
            FetchFastApiStats::dispatch();
            \Illuminate\Support\Facades\Log::info('Jobs dispatched from index');
        }
        $laravelStats = $isSystemInfoEnabled ? Cache::get('laravel_stats', []) : [];
        $fastapistats = $isSystemInfoEnabled ? Cache::get('fastapi_stats', []) : [];
        // Kiểm tra ngưỡng và tạo cảnh báo
        $alerts = [];
        if ($isSystemInfoEnabled) {
            $ramUsagePercent = $laravelStats['ram_total'] > 0 ? ($laravelStats['ram_used'] / $laravelStats['ram_total']) * 100 : 0;
            $gpuUsagePercent = $laravelStats['gpu_total'] > 0 ? ($laravelStats['gpu_used'] / $laravelStats['gpu_total']) * 100 : 0;
            // if ($laravelStats['cpu_usage'] > 90) {
            //     $alerts[] = 'CPU (Laravel) vượt ngưỡng 90%: ' . $laravelStats['cpu_usage'] . '%';
            // }
            // if ($ramUsagePercent > 90) {
            //     $alerts[] = 'RAM (Laravel) vượt ngưỡng 90%: ' . round($ramUsagePercent, 2) . '%';
            // }
            // if ($laravelStats['gpu_usage'] > 90 || $gpuUsagePercent > 90) {
            //     $alerts[] = 'GPU (Laravel) vượt ngưỡng 90%: ' . $laravelStats['gpu_usage'] . '% (Utilization) hoặc ' . round($gpuUsagePercent, 2) . '% (Memory)';
            // }
            // if ($fastapistats['cpu_usage_percent'] > 90) {
            //     $alerts[] = 'CPU (FastAPI) vượt ngưỡng 90%: ' . $fastapistats['cpu_usage_percent'] . '%';
            // }
            // if ($fastapistats['ram_usage_percent'] > 90) {
            //     $alerts[] = 'RAM (FastAPI) vượt ngưỡng 90%: ' . $fastapistats['ram_usage_percent'] . '%';
            // }
            // if ($fastapistats['gpu_usage_percent'] > 90) {
            //     $alerts[] = 'GPU (FastAPI) vượt ngưỡng 90%: ' . $fastapistats['gpu_usage_percent'] . '%';
            // }
        }
        // Lấy giao diện hiện tại từ database
        $currentTheme = Setting::where('key', 'theme')->first()->value ?? 'index';
        $users = User::with('loginHistories')->get();//lưu lịch sử đăng nhập
        $rechargeRequests = RechargeRequest::where('status', 'pending')->get();
        $totalRevenue = RechargeHistory::sum('amount');
        $averageRating = User::avg('rating') ?? 0;
        // Thêm số liệu mới
        $approvedRequests = RechargeRequest::where('status', 'approved')->count();
        $rejectedRequests = RechargeRequest::where('status', 'rejected')->count();
        // Thêm số liệu cho người dùng hoạt động và không hoạt động
        $activeUsers = User::where('status', 'active')->count();
        $inactiveUsers = User::where('status', 'inactive')->count();
        // Cập nhật để tính doanh thu theo ngày
        $revenueData = RechargeHistory::select(
            DB::raw('DATE_FORMAT(approved_at, "%Y-%m-%d") as day'),
            DB::raw('SUM(amount) as total')
        )
            ->whereNotNull('approved_at')
            ->groupBy('day')
            ->orderBy('day')
            ->get();
        // Lấy múi giờ hiện tại từ bảng settings
        $currentTimezone = \App\Models\Setting::where('key', 'timezone')->first()->value ?? config('app.timezone');
        // Lấy lịch sử giao dịch (tất cả các yêu cầu nạp tiền)
        $transactionHistory = RechargeRequest::with('user')
            ->orderBy('created_at', 'desc')
            //     ->take(10) // Giới hạn 10 giao dịch gần nhất để tránh quá tải
            ->get();
        // Tính tổng doanh thu
        $totalRevenue = RechargeRequest::where('status', 'approved')->sum('amount');
        //Tính doanh thu theo từng user
        $revenueByUser = RechargeRequest::select('user_id')
            ->selectRaw('SUM(amount) as total_revenue')
            ->where('status', 'approved')
            ->groupBy('user_id')
            ->with('user') // Lấy thông tin user liên quan
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->user_id => [
                        'name' => $item->user ? $item->user->name : 'Người dùng không tồn tại',
                        'total_revenue' => $item->total_revenue,
                    ]
                ];
            });
        $revenueLabels = $revenueData->pluck('day')->toArray();
        $revenueData = $revenueData->pluck('total')->toArray();
        if (empty($revenueLabels)) {
            $revenueLabels = ['Chưa có dữ liệu'];
            $revenueData = [0];
        }
        // Lấy danh sách người dùng đã gửi tin nhắn
        $chatUsers = User::whereHas('messages', function ($query) {
            $query->whereNotNull('message');
        })->get();
        //Lịch sử giao dịch trang admin
        $classifications = \App\Models\Classification::with('user')->get();
        //dd($classifications);
        //dữ liệu từ bảng ceramics
        $ceramics = Ceramic::all();
        //Chính sáchd  và điều khoản
        $terms = \App\Models\TermsAndConditions::first();
        // Lấy trạng thái CAPTCHA từ cột mới
        $recaptchaEnabled = Setting::where('key', 'recaptcha_enabled')->first();
        $recaptchaEnabled = $recaptchaEnabled ? ($recaptchaEnabled->recaptcha_enabled == 1) : false;
        //Liên hệ
        $contacts = \App\Models\Contact::latest()->get();
        //stats cho 4 tabtổng quan
        $userTrend = $this->getTrendData(User::class, 'created_at');
        $rechargeTrend = $this->getTrendData(RechargeRequest::class, 'created_at', ['status' => 'pending']);
        $revenueTrend = $this->getTrendData(RechargeRequest::class, 'created_at', ['status' => 'approved'], 'amount');
        $ratingTrend = $this->getTrendData(User::class, 'updated_at', [], 'rating');
        //cập nhật tin tức
        $news = News::all();
        // Tổng số lượt nhận diện (tokens_used) từ bảng users
        $totalTokenUsed = User::sum('tokens_used'); // Lấy tổng từ cột tokens_used trong bảng users
        // Xu hướng sử dụng token (trend data) trong 7 ngày
        $tokenTrend = $this->getTrendData(User::class, 'created_at', [], 'tokens_used');
        return view('admin', compact('users', 'rechargeRequests', 'totalTokenUsed', 'tokenTrend','fastapistats', 'totalRevenue', 'averageRating', 'revenueLabels', 'revenueData', 'chatUsers', 'transactionHistory', 'ceramics', 'currentTimezone', 'classifications', 'terms', 'recaptchaEnabled', 'revenueByUser', 'currentTheme', 'contacts', 'userTrend', 'rechargeTrend', 'revenueTrend', 'ratingTrend', 'chatUsers', 'news', 'approvedRequests', 'rejectedRequests', 'activeUsers', 'inactiveUsers', 'laravelStats', 'isSystemInfoEnabled', 'alerts'));
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
    }
    public function edit($id)
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
            'status' => 'required|in:active,inactive', // Validate status
        ]);
        // Cập nhật dữ liệu
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'role' => $request->input('role'),
            'tokens' => $request->input('tokens'),
            'status' => $request->input('status'), // Update status
        ]);
        // Xóa dòng $user->save() vì update() đã tự động lưu
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
    //Settings thay đổi múi giờ
    public function updateTimezone(Request $request)
    {
        // Xác thực dữ liệu đầu vào
        $request->validate([
            'timezone' => 'required|string|timezone',
        ]);
        // Lưu múi giờ vào bảng settings
        Setting::updateOrCreate(
            ['key' => 'timezone'],
            ['value' => $request->timezone]
        );
        // Cập nhật múi giờ cho ứng dụng
        config(['app.timezone' => $request->timezone]);
        date_default_timezone_set($request->timezone);
        // Chuyển hướng về trang trước đó với thông báo thành công
        return redirect()->back()->with('timezone_success', 'Múi giờ đã được cập nhật thành công!');
    }
    //Lịch sử giao dịch
    public function getRecognitionHistory(Request $request)
    {
        $query = RecognitionHistory::with('user')
            ->orderBy('created_at', 'desc');
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->has('method') && $request->method) {
            $query->where('method', $request->method);
        }
        if ($request->has('date') && $request->date) {
            $query->whereDate('created_at', $request->date);
        }
        $history = $query->paginate(15);
        return view('admin.dashboard', [
            'recognitionHistory' => $history,
            // Các biến khác...
        ]);
    }
    //Chính sách và điều khoản
    public function terms()
    {
        $terms = TermsAndConditions::first();
        return view('admin.terms', compact('terms'));
    }
    public function updateTerms(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
        ]);
        $terms = TermsAndConditions::first();
        if ($terms) {
            $terms->update(['content' => $request->content]);
        } else {
            TermsAndConditions::create(['content' => $request->content]);
        }
        return redirect()->route('admin.index')->with('success', 'Chính sách và điều khoản đã được cập nhật.');
    }
    //Xuât file excel
    public function exportTransactionHistory(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $query = RechargeRequest::with('user');
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
        $transactions = $query->orderBy('created_at', 'desc')->get();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        // Thiết lập tiêu đề
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Tên Người Dùng');
        $sheet->setCellValue('C1', 'Số Tiền (VNĐ)');
        $sheet->setCellValue('D1', 'Tokens Yêu Cầu');
        $sheet->setCellValue('E1', 'Trạng Thái');
        $sheet->setCellValue('F1', 'Thời Gian');
        // Thêm dữ liệu
        $row = 2;
        foreach ($transactions as $transaction) {
            $sheet->setCellValue('A' . $row, $transaction->id);
            $sheet->setCellValue('B' . $row, $transaction->user->name ?? 'Người dùng không tồn tại');
            $sheet->setCellValue('C' . $row, number_format($transaction->amount));
            $sheet->setCellValue('D' . $row, $transaction->requested_tokens);
            $sheet->setCellValue('E' . $row, ucfirst($transaction->status));
            $sheet->setCellValue('F' . $row, $transaction->created_at->format('d/m/Y H:i'));
            $row++;
        }
        $writer = new Xlsx($spreadsheet);
        $fileName = 'transaction_history_' . now()->format('Ymd_His') . '.xlsx';
        // Lưu vào file tạm
        $tempFile = tempnam(sys_get_temp_dir(), 'transaction_history');
        $writer->save($tempFile);
        // Trả về file dưới dạng tải xuống
        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }
    //bật tắt capcha
    public function updateCaptchaSetting(Request $request)
    {
        // Kiểm tra giá trị của checkbox (1 nếu bật, 0 nếu tắt)
        $recaptchaEnabled = $request->has('recaptcha_enabled') ? '1' : '0';
        // Cập nhật hoặc tạo bản ghi trong bảng settings
        Setting::updateOrCreate(
            ['key' => 'recaptcha_enabled'],
            ['recaptcha_enabled' => $recaptchaEnabled]
        );
        // Debug: Kiểm tra giá trị sau khi lưu
        $newValue = Setting::where('key', 'recaptcha_enabled')->first()->value;
        \Log::info("CAPTCHA state updated to: " . $newValue);
        return redirect()->back()->with('captcha_success', 'Cài đặt CAPTCHA đã được cập nhật thành công!');
    }
    //Thống kê số lượt sử dụng
    public function showTokenUsage(User $user)
    {
        $tokenUsages = $user->tokenUsages()->latest()->paginate(10);
        return view('admin.token-usage', compact('user', 'tokenUsages'));
    }
    //Chọn giao diện
    public function updateTheme(Request $request)
    {
        $request->validate([
            'theme' => 'required|in:index,index2',
        ]);
        // Cập nhật hoặc tạo mới setting trong database
        Setting::updateOrCreate(
            ['key' => 'theme'],
            ['value' => $request->theme]
        );
        return redirect()->back()->with('theme_success', 'Giao diện đã được cập nhật thành công!');
    }
    private function getTrendData($model, $dateColumn, $conditions = [], $valueColumn = null)
    {
        $query = $model::query();
        foreach ($conditions as $key => $value) {
            $query->where($key, $value);
        }
        $data = $query
            ->selectRaw("DATE($dateColumn) as date, " . ($valueColumn ? "AVG($valueColumn)" : 'COUNT(*)') . " as value")
            ->where($dateColumn, '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('value', 'date')
            ->toArray();
        $labels = [];
        $values = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->toDateString();
            $labels[] = Carbon::parse($date)->format('d/m');
            $values[] = $data[$date] ?? ($valueColumn ? null : 0);
        }
        return ['labels' => $labels, 'values' => $values];
    }
    // Hàm hỗ trợ lấy thông tin hệ thống
    public function laravelStats()
    {
        $optimizationSetting = Setting::where('key', 'system_info_optimization')->first();
        if (!$optimizationSetting || $optimizationSetting->value !== 'enabled') {
            return response()->json(['message' => 'Thông tin hệ thống bị tắt']);
        }
        // Dispatch job mỗi lần gọi API
        FetchLaravelStats::dispatch();
        return response()->json(Cache::get('laravel_stats', []));
    }
    public function fastApiStats()
    {
        $optimizationSetting = Setting::where('key', 'system_info_optimization')->first();
        if (!$optimizationSetting || $optimizationSetting->value !== 'enabled') {
            return response()->json(['message' => 'Thông tin hệ thống bị tắt']);
        }
        // Dispatch job mỗi lần gọi API
        FetchFastApiStats::dispatch();
        return response()->json(Cache::get('fastapi_stats', []));
    }
    public function toggleOptimization(Request $request)
    {
        $optimization = $request->has('optimization') ? 'enabled' : 'disabled';
        Setting::updateOrCreate(
            ['key' => 'system_info_optimization'],
            ['value' => $optimization]
        );
        if ($optimization === 'disabled') {
            Cache::forget('laravel_stats');
            Cache::forget('fastapi_stats');
        }
        return redirect()->route('admin.index')->with('success', 'Cập nhật trạng thái tối ưu hóa thành công.');
    }
    //Sao lưu dữ liệu
    public function backup()
    {
        try {
            // Lấy thông tin cấu hình database từ file .env
            $dbName = config('database.connections.mysql.database');
            $dbUser = config('database.connections.mysql.username');
            $dbPass = config('database.connections.mysql.password');
            $dbHost = config('database.connections.mysql.host');
            // Ghi log cấu hình để kiểm tra
            \Log::info("Backup config: DB=$dbName, User=$dbUser, Host=$dbHost");
            // Đặt tên tệp sao lưu
            $fileName = 'database_backup_' . date('Ymd_His') . '.sql';
            $filePath = storage_path('app/backups/' . $fileName);
            // Tạo thư mục backups nếu chưa tồn tại
            if (!file_exists(storage_path('app/backups'))) {
                mkdir(storage_path('app/backups'), 0755, true);
            }
            // Mở tệp để ghi
            $file = fopen($filePath, 'w');
            // Thêm tiêu đề tệp SQL
            fwrite($file, "-- Database Backup for $dbName\n");
            fwrite($file, "-- Generated on " . now()->toDateTimeString() . "\n");
            fwrite($file, "-- Host: $dbHost\n");
            fwrite($file, "-- MySQL Version: " . DB::selectOne('SELECT VERSION() as version')->version . "\n\n");
            fwrite($file, "SET FOREIGN_KEY_CHECKS = 0;\n\n");
            // Lấy danh sách bảng
            $tables = DB::select('SHOW TABLES');
            $tableCount = count($tables);
            \Log::info("Found $tableCount tables to backup");
            if ($tableCount == 0) {
                fclose($file);
                throw new \Exception("Cơ sở dữ liệu không có bảng nào để sao lưu.");
            }
            // Duyệt qua từng bảng
            foreach ($tables as $table) {
                $tableName = array_values((array) $table)[0]; // Lấy tên bảng từ kết quả SHOW TABLES
                // Lấy câu lệnh CREATE TABLE
                $createTable = DB::selectOne("SHOW CREATE TABLE `$tableName`")->{'Create Table'};
                fwrite($file, "-- Table structure for `$tableName`\n");
                fwrite($file, "DROP TABLE IF EXISTS `$tableName`;\n");
                fwrite($file, "$createTable;\n\n");
                // Lấy dữ liệu từ bảng
                $rows = DB::table($tableName)->get();
                $rowCount = $rows->count();
                if ($rowCount > 0) {
                    fwrite($file, "-- Dumping data for `$tableName`\n");
                    $columns = array_keys((array) $rows->first());
                    // Chuẩn bị câu lệnh INSERT
                    $insertBase = "INSERT INTO `$tableName` (`" . implode('`, `', $columns) . "`) VALUES\n";
                    $values = [];
                    foreach ($rows as $row) {
                        $rowValues = array_map(function ($value) {
                            return $value === null ? 'NULL' : DB::getPdo()->quote($value);
                        }, array_values((array) $row));
                        $values[] = '(' . implode(', ', $rowValues) . ')';
                    }
                    // Ghi dữ liệu vào tệp
                    fwrite($file, $insertBase);
                    fwrite($file, implode(",\n", $values) . ";\n\n");
                } else {
                    fwrite($file, "-- No data in `$tableName`\n\n");
                }
                \Log::info("Backed up table: $tableName, Rows: $rowCount");
            }
            // Thêm lệnh khôi phục kiểm tra khóa ngoại
            fwrite($file, "SET FOREIGN_KEY_CHECKS = 1;\n");
            // Đóng tệp
            fclose($file);
            // Kiểm tra kích thước tệp
            $fileSize = filesize($filePath);
            \Log::info("Backup file created: $filePath, Size: $fileSize bytes");
            if ($fileSize < 10) {
                throw new \Exception("Tệp sao lưu rỗng hoặc không chứa dữ liệu.");
            }
            // Trả về tệp tải xuống và xóa sau khi gửi
            return response()->download($filePath, $fileName)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            \Log::error('Backup failed: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Sao lưu dữ liệu thất bại: ' . $e->getMessage());
        }
    }
}