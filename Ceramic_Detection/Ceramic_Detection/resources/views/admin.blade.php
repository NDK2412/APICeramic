<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Quản Lý Người Dùng</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="{{ asset('css/settings.css') }}">
    <link rel="stylesheet" href="{{ asset('css/HistoryDetection.css') }}">
    <link rel="stylesheet" href="{{ asset('css/Terms.css') }}">
    <style>
        :root {
            --primary-color: rgb(0, 0, 0);
            --secondary-color: #42a5f5;
            --accent-color: #eceff1;
            --light-color: #f5f7fa;
            --dark-color: #263238;
            --success-color: #00c853;
            --warning-color: #ffca28;
            --error-color: #f44336;
            --gradient: linear-gradient(135deg, rgb(137, 58, 58), var(--secondary-color));
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: var(--light-color);
            color: var(--dark-color);
            min-height: 100vh;
            display: flex;
        }

        .sidebar {
            width: 250px;
            background: var(--dark-color);
            color: white;
            height: 100vh;
            position: fixed;
            padding: 20px;
            transition: width 0.3s;
        }

        .sidebar h2 {
            font-size: 1.5rem;
            margin-bottom: 30px;
            text-align: center;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar li {
            margin-bottom: 15px;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            font-size: 1rem;
            display: flex;
            align-items: center;
            padding: 10px;
            border-radius: 8px;
            transition: background 0.3s;
        }

        .sidebar a:hover, .sidebar a.active {
            background: var(--gradient);
        }

        .sidebar a i {
            margin-right: 10px;
        }

        .content {
            margin-left: 250px;
            flex: 1;
            padding: 20px;
        }

        .container {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.5s ease-in;
        }

        h1 {
            font-size: 2rem;
            font-weight: 600;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 20px;
            text-align: center;
        }

        h3 {
            font-size: 1.4rem;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .stats {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            flex: 1;
            padding: 20px;
            background: var(--gradient);
            color: white;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card h3 {
            font-size: 1.2rem;
            margin-bottom: 10px;
        }

        .stat-card p {
            font-size: 1.5rem;
            font-weight: 600;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid var(--accent-color);
        }

        th {
            background: var(--primary-color);
            color: white;
        }

        tr:hover {
            background: var(--light-color);
        }

        .actions {
            display: flex;
            gap: 6px;
        }

        .action-btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .edit-btn { background: var(--warning-color); color: var(--dark-color); }
        .edit-btn:hover { background: #ffb300; transform: translateY(-2px); }
        .save-btn { background: var(--success-color); color: white; }
        .save-btn:hover { background: #00a843; transform: translateY(-2px); }
        .cancel-btn { background: #ccc; color: var(--dark-color); }
        .cancel-btn:hover { background: #b0b0b0; transform: translateY(-2px); }
        .delete-btn { background: var(--error-color); color: white; }
        .delete-btn:hover { background: #d32f2f; transform: translateY(-2px); }
        .reject-btn { background: #ff5722; color: white; }
        .reject-btn:hover { background: #e64a19; transform: translateY(-2px); }

        .status {
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .status.pending {
            background: var(--warning-color);
            color: white;
        }

        .status.approved {
            background: var(--success-color);
            color: white;
        }

        .status.rejected {
            background: var(--error-color);
            color: white;
        }

        .logout-btn {
            background: var(--gradient);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
            display: block;
            margin: 20px auto;
        }

        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .success-message {
            color: var(--success-color);
            background: #d4edda;
            padding: 8px;
            border-radius: 4px;
            margin-bottom: 15px;
            text-align: center;
        }

        .user-name {
            cursor: pointer;
            color: var(--primary-color);
            text-decoration: underline;
        }

        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            width: 400px;
            max-width: 90%;
        }

        .popup-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .popup h3 {
            font-size: 1.4rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .popup .rating-stars {
            font-size: 1.5rem;
            color: var(--warning-color);
            margin-bottom: 1rem;
        }

        .popup p {
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .popup textarea, .popup input {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid var(--accent-color);
            border-radius: 4px;
        }

        .popup button {
            background: var(--gradient);
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 1rem;
        }

        #revenueChart {
            max-width: 800px;
            margin: 0;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @media (max-width: 768px) {
            .sidebar { width: 80px; }
            .sidebar a { font-size: 0; }
            .sidebar a i { margin-right: 0; font-size: 1.2rem; }
            .content { margin-left: 80px; }
            .stats { flex-direction: column; }
            table { font-size: 0.75rem; }
        }
        /* Thêm vào phần style */
        .action-btn.save-btn i {
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Quản Lý</h2>
        <ul>
            <li><a href="#" data-tab="overview" class="active"><i class="fas fa-tachometer-alt"></i> Tổng quan</a></li>
            <li><a href="#" data-tab="users"><i class="fas fa-users"></i> Quản lý người dùng</a></li>
            <li><a href="#" data-tab="recharge"><i class="fas fa-money-bill"></i> Yêu cầu nạp tiền</a></li>
            <li><a href="#" data-tab="revenue"><i class="fas fa-chart-line"></i> Doanh thu</a></li>
            <li><a href="#" data-tab="ceramics"><i class="fa-solid fa-layer-group"></i> Quản lý thư viện đồ gốm</a></li>
            <li><a href="#" data-tab="classifications"><i class="fas fa-history"></i> Lịch Sử Nhận Diện</a></li>
            <li><a href="#" data-tab="terms"><i class="fas fa-file-alt"></i> Chính sách và điều khoản</a></li>
            <li><a href="#" data-tab="settings"><i class="fas fa-cog"></i> Cài Đặt</a></li>
            <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
        </ul>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>

    <div class="content">
        <!-- Tab Tổng quan -->
        <div class="container tab-content" id="overview">
            <h1>Tổng Quan</h1>
            <div class="stats">
                <div class="stat-card">
                    <h3>Tổng người dùng</h3>
                    <p>{{ $users->count() }}</p>
                </div>
                <div class="stat-card">
                    <h3>Yêu cầu chờ duyệt</h3>
                    <p>{{ $rechargeRequests->count() }}</p>
                </div>
                <div class="stat-card">
                    <h3>Tổng doanh thu</h3>
                    <p>{{ number_format($totalRevenue) }} VNĐ</p>
                </div>
                <div class="stat-card">
                    <h3>Đánh giá trung bình</h3>
                    <p>{{ number_format($averageRating, 1) }}/5</p>
                </div>
            </div>

            <!-- Bảng lịch sử giao dịch -->
            <div class="transaction-history">
                <h3>Lịch Sử Giao Dịch</h3>
                <form action="{{ route('admin.export.transaction.history') }}" method="GET" style="margin-bottom: 20px;">
                    <div style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
                        <div>
                            <label for="start_date">Từ ngày:</label>
                            <input type="date" id="start_date" name="start_date" required>
                        </div>
                        <div>
                            <label for="end_date">Đến ngày:</label>
                            <input type="date" id="end_date" name="end_date" required>
                        </div>
                        <button type="submit" class="action-btn save-btn">
                            <i class="fas fa-file-excel"></i> Xuất Excel
                        </button>
                    </div>
                </form>
                @if ($transactionHistory->isEmpty())
                    <p>Không có giao dịch nào.</p>
                @else
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên người dùng</th>
                                <th>Số tiền</th>
                                <th>Tokens yêu cầu</th>
                                <th>Trạng thái</th>
                                <th>Thời gian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactionHistory as $transaction)
                                <tr>
                                    <td>{{ $transaction->id }}</td>
                                    <td>{{ $transaction->user->name ?? 'Người dùng không tồn tại' }}</td>
                                    <td>{{ number_format($transaction->amount) }} VNĐ</td>
                                    <td>{{ $transaction->requested_tokens }}</td>
                                    <td>
                                        <span class="status {{ $transaction->status }}">{{ ucfirst($transaction->status) }}</span>
                                    </td>
                                    <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
             <!-- Bảng Doanh Thu Theo Người Dùng -->
 <div class="revenue-by-user">
        <h3>Doanh Thu Theo Người Dùng</h3>
        @if ($revenueByUser->isEmpty())
            <p>Không có dữ liệu doanh thu.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Tên người dùng</th>
                        <th>Doanh thu (VNĐ)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($revenueByUser as $userId => $data)
                        <tr>
                            <td>{{ $data['name'] }}</td>
                            <td>{{ number_format($data['total_revenue']) }} VNĐ</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
        

<!-- Tab Quản lý người dùng -->
<div class="container tab-content" id="users" style="display: none;">
    <h1>Quản Lý Người Dùng</h1>
    @if (session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div style="color: var(--error-color); background: #f8d7da; padding: 8px; border-radius: 4px; margin-bottom: 15px; text-align: center;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Email</th>
                <th>Vai trò</th>
                <th>Tokens </th>
                <th>Tokens đã dùng</th>
                <th>Hành động</th>
                <th>Lịch Sử Đăng Nhập</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr id="row-{{ $user->id }}">
                    <td>{{ $user->id }}</td>
                    <td class="editable" data-field="name">
                        <span class="display user-name" onclick="showPopup('{{ $user->id }}', '{{ $user->name }}', '{{ $user->rating ?? 0 }}', '{{ $user->feedback ?? 'Chưa có phản hồi' }}')">{{ $user->name }}</span>
                        <input type="text" name="name" value="{{ $user->name }}" style="display:none;">
                    </td>
                    <td class="editable" data-field="email">
                        <span class="display">{{ $user->email }}</span>
                        <input type="email" name="email" value="{{ $user->email }}" style="display:none;">
                    </td>
                    <td class="editable" data-field="role">
                        <span class="display">{{ $user->role }}</span>
                        <select name="role" style="display:none;">
                            <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>Người dùng</option>
                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </td>
                    <td class="editable" data-field="tokens">
                        <span class="display">{{ $user->tokens }}</span>
                        <input type="number" name="tokens" value="{{ $user->tokens }}" style="display:none;" min="0">
                    </td>
                    <td>
                        {{ $user->tokens_used }}
                        <a href="{{ route('admin.users.token-usage', $user) }}" 
                           style="margin-left: 5px; padding: 3px 6px;">
                        </a>
                    </td>
                    <td class="actions">
                        <!-- Form "Lưu" -->
                        <form action="{{ route('admin.update', $user->id) }}" method="POST" class="edit-form" id="form-{{ $user->id }}" style="display:inline;">
                            @csrf
                            @method('PUT')
                            <button type="button" class="action-btn edit-btn" onclick="editRow({{ $user->id }})"><i class="fas fa-edit"></i> Sửa</button>
                            <button type="submit" class="action-btn save-btn" style="display:none;"><i class="fas fa-save"></i> Lưu</button>
                            <button type="button" class="action-btn cancel-btn" style="display:none;" onclick="cancelEdit({{ $user->id }})"><i class="fas fa-times"></i> Hủy</button>
                        </form>
                        <!-- Form "Xóa" -->
                        <form action="{{ route('admin.delete', $user->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc muốn xóa người dùng này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn delete-btn"><i class="fas fa-trash"></i> Xóa</button>
                        </form>
                    </td>
                    <td>
                        <button class="action-btn save-btn" onclick="showLoginHistory('{{ $user->id }}', '{{ $user->name }}')">
                            <i class="fas fa-eye"></i> Xem
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<!-- Popup Lịch Sử Đăng Nhập -->
<div class="popup-overlay" id="loginHistoryOverlay" onclick="hideLoginHistory()"></div>
<div class="popup" id="loginHistoryPopup">
    <h3>Lịch Sử Đăng Nhập của <span id="loginHistoryUserName"></span></h3>
    <div id="loginHistoryContent">
        <table>
            <thead>
                <tr>
                    <th>Thời Gian</th>
                    <th>Địa Chỉ IP</th>
                    <th>Thiết Bị</th>
                </tr>
            </thead>
            <tbody id="loginHistoryTable">
                <!-- Nội dung sẽ được thêm bằng JavaScript -->
            </tbody>
        </table>
    </div>
    <button onclick="hideLoginHistory()">Đóng</button>
</div>
        <!-- Tab Yêu cầu nạp tiền -->
        <div class="container tab-content" id="recharge" style="display: none;">
            <h1>Yêu Cầu Nạp Tiền</h1>
            @if ($rechargeRequests->isEmpty())
                <p>Không có yêu cầu nào đang chờ.</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên người dùng</th>
                            <th>Số tiền</th>
                            <th>Tokens yêu cầu</th>
                            <th>Ảnh chứng minh</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rechargeRequests as $request)
                            <tr>
                                <td>{{ $request->id }}</td>
                                <td>{{ $request->user->name }}</td>
                                <td>{{ number_format($request->amount) }} VNĐ</td>
                                <td>{{ $request->requested_tokens }}</td>
                                <td>
                                    @if ($request->proof_image)
                                        <a href="{{ url('/storage/' . $request->proof_image) }}" target="_blank">
                                            <img src="{{ url('/storage/' . $request->proof_image) }}" alt="Proof" style="max-width: 100px; border-radius: 5px;">
                                        </a>
                                    @else
                                        <p>Không có ảnh</p>
                                    @endif
                                </td>
                                <td class="actions">
                                    <form action="{{ route('admin.recharge.approve', $request->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="action-btn save-btn"><i class="fas fa-check"></i> Xác nhận</button>
                                    </form>
                                    <button type="button" class="action-btn reject-btn" onclick="showRejectPopup('{{ $request->id }}', '{{ $request->user->name }}')"><i class="fas fa-times"></i> Không xác nhận</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Tab Doanh thu -->
        <div class="container tab-content" id="revenue" style="display: none;">
            <h1>Doanh Thu Theo Thời Gian</h1>
            <canvas id="revenueChart" width="800" height="400"></canvas>
        </div>

        <!-- Tab Quản lý thư viện đồ gốm -->
        <div class="container tab-content" id="ceramics" style="display: none;">
            <h1>Quản Lý Thư Viện Đồ Gốm</h1>

            <!-- Nút thêm món đồ gốm mới -->
            <button type="button" class="action-btn save-btn" onclick="showAddCeramicPopup()" style="margin-bottom: 20px;">
                <i class="fas fa-plus"></i> Thêm món đồ gốm mới
            </button>

            <!-- Thông báo thành công (nếu có) -->
            @if (session('success'))
                <div class="success-message">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Bảng danh sách đồ gốm -->
            @if ($ceramics->isEmpty())
                <p>Không có món đồ gốm nào trong thư viện.</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên</th>
                            <th>Mô tả</th>
                            <th>Hình ảnh</th>
                            <th>Danh mục</th>
                            <th>Nguồn gốc</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($ceramics as $ceramic)
                        <tr id="ceramic-row-{{ $ceramic->id }}">
                            <form action="{{ route('admin.ceramics.update', $ceramic->id) }}" method="POST" class="edit-form" id="ceramic-form-{{ $ceramic->id }}">
                                @csrf
                                @method('PUT')
                                <td>{{ $ceramic->id }}</td>
                                <td class="editable" data-field="name">
                                    <span class="display">{{ $ceramic->name }}</span>
                                    <input type="text" name="name" value="{{ $ceramic->name }}" style="display:none;">
                                </td>
                                <td class="editable" data-field="description">
                                    <span class="display">{{ $ceramic->description ?? 'Không có' }}</span>
                                    <textarea name="description" style="display:none;">{{ $ceramic->description }}</textarea>
                                </td>
                                <td class="editable" data-field="image">
                                    <span class="display">
                                        @if ($ceramic->image)
                                            <img src="{{ url('/storage/' . $ceramic->image) }}" alt="{{ $ceramic->name }}" style="max-width: 100px; border-radius: 5px;">
                                        @else
                                            Không có ảnh
                                        @endif
                                    </span>
                                    <input type="text" name="image" value="{{ $ceramic->image }}" style="display:none;" placeholder="Đường dẫn hình ảnh (ceramics/ten_hinh.jpg)">
                                </td>
                                <td class="editable" data-field="category">
                                    <span class="display">{{ $ceramic->category ?? 'Không có' }}</span>
                                    <input type="text" name="category" value="{{ $ceramic->category }}" style="display:none;">
                                </td>
                                <td class="editable" data-field="origin">
                                    <span class="display">{{ $ceramic->origin ?? 'Không có' }}</span>
                                    <input type="text" name="origin" value="{{ $ceramic->origin }}" style="display:none;">
                                </td>
                                <td class="actions">
                                    <button type="button" class="action-btn edit-btn" onclick="editCeramicRow({{ $ceramic->id }})"><i class="fas fa-edit"></i> Sửa</button>
                                    <form action="{{ route('admin.ceramics.update', $user->id) }}" method="POST" class="edit-form" id="form-{{ $user->id }}">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="action-btn save-btn" style="display:none;"><i class="fas fa-save"></i> Lưu</button>
                                </form>
                                    
                                    <button type="button" class="action-btn cancel-btn" style="display:none;" onclick="cancelCeramicEdit({{ $ceramic->id }})"><i class="fas fa-times"></i> Hủy</button>
                                    <form action="{{ route('admin.ceramics.delete', $ceramic->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc muốn xóa món đồ gốm này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn delete-btn"><i class="fas fa-trash"></i> Xóa</button>
                                    </form>
                                </td>
                            </form>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        <!-- Tab Yêu Cầu Settings -->
        <div class="container tab-content" id="settings" style="display: none;">
            <h1>Cài Đặt</h1>
            <h3>Thay Đổi Múi Giờ</h3>
            @if (session('timezone_success'))
                <div class="success-message">
                    {{ session('timezone_success') }}
                </div>
            @endif
            <form method="POST" action="{{ route('admin.settings.timezone') }}">
                @csrf
                <div>
                    <label for="timezone">Chọn múi giờ:</label>
                    <select name="timezone" id="timezone" required>
                        <option value="" disabled {{ !isset($currentTimezone) ? 'selected' : '' }}>Chọn múi giờ</option>
                        <option value="UTC" {{ isset($currentTimezone) && $currentTimezone === 'UTC' ? 'selected' : '' }}>UTC</option>
                        <option value="Asia/Ho_Chi_Minh" {{ isset($currentTimezone) && $currentTimezone === 'Asia/Ho_Chi_Minh' ? 'selected' : '' }}>Asia/Ho_Chi_Minh (Việt Nam, GMT+7)</option>
                        <option value="Asia/Bangkok" {{ isset($currentTimezone) && $currentTimezone === 'Asia/Bangkok' ? 'selected' : '' }}>Asia/Bangkok (Thái Lan, GMT+7)</option>
                        <option value="Asia/Tokyo" {{ isset($currentTimezone) && $currentTimezone === 'Asia/Tokyo' ? 'selected' : '' }}>Asia/Tokyo (Nhật Bản, GMT+9)</option>
                        <option value="America/New_York" {{ isset($currentTimezone) && $currentTimezone === 'America/New_York' ? 'selected' : '' }}>America/New_York (Mỹ, GMT-5)</option>
                        <option value="Europe/London" {{ isset($currentTimezone) && $currentTimezone === 'Europe/London' ? 'selected' : '' }}>Europe/London (Anh, GMT+0)</option>
                    </select>
                </div>
                <button type="submit" class="action-btn save-btn"><i class="fas fa-save"></i> Lưu Múi Giờ</button>
            </form>
            <!-- Bật/Tắt CAPTCHA -->
    <h3>Bật/Tắt CAPTCHA cho Trang Đăng Nhập</h3>
    @if (session('captcha_success'))
        <div class="success-message">
            {{ session('captcha_success') }}
        </div>
    @endif
    <form method="POST" action="{{ route('admin.settings.captcha') }}">
        @csrf
        <div>
            <label for="recaptcha_enabled">
                <input type="checkbox" id="recaptcha_enabled" name="recaptcha_enabled" value="1" {{ $recaptchaEnabled ? 'checked' : '' }}>
                Bật CAPTCHA (reCAPTCHA) cho trang đăng nhập
            </label>
        </div>
        <button type="submit" class="action-btn save-btn"><i class="fas fa-save"></i> Lưu Cài Đặt</button>
    </form>
        </div>

        <!-- Tab lịch sử nhận diện -->
         <!-- Tab Lịch Sử Nhận Diện -->
<div class="container tab-content" id="classifications" style="display: none;">
    <h1>Lịch Sử Nhận Diện</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên Người Dùng</th>
                <th>Email</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td class="user-name" onclick="showClassificationHistory('{{ $user->id }}', '{{ $user->name }}')">{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <button class="action-btn save-btn" onclick="showClassificationHistory('{{ $user->id }}', '{{ $user->name }}')">
                            <i class="fas fa-eye"></i> Xem Lịch Sử
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Tab chỉnh sủa lịch sử nhận diện -->
     

</div>
<!-- Tab Chính sách và điều khoản -->
<div class="container tab-content" id="terms" style="display: none;">
    <h1>Chính sách và điều khoản</h1>
    @if (session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif
    <form method="POST" action="{{ route('admin.terms.update') }}">
        @csrf
        <div>
            <label for="terms_content">Nội dung chính sách và điều khoản:</label>
            <textarea name="content" id="terms_content" rows="10" required>{{ $terms ? $terms->content : '' }}</textarea>
        </div>
        <button type="submit" class="action-btn save-btn"><i class="fas fa-save"></i> Lưu</button>
    </form>
</div>
    </div>
<!-- Popup Lịch Sử Nhận Diện -->
<div class="popup-overlay" id="classificationOverlay" onclick="hideClassificationHistory()"></div>
<div class="popup" id="classificationPopup">
    <h3>Lịch Sử Nhận Diện của <span id="classificationUserName"></span></h3>
    <div id="classificationHistoryContent">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ảnh</th>
                    <th>Kết Quả</th>
                    <th>Thời Gian</th>
                </tr>
            </thead>
            <tbody id="classificationHistoryTable">
                <!-- Nội dung sẽ được thêm bằng JavaScript -->
            </tbody>
        </table>
    </div>
    <button onclick="hideClassificationHistory()">Đóng</button>
</div>
    <!-- Popup thông tin đánh giá -->
    <div class="popup-overlay" onclick="hidePopup()"></div>
    <div class="popup" id="userPopup">
        <h3>Thông tin đánh giá của <span id="popupName"></span></h3>
        <div class="rating-stars" id="popupRating"></div>
        <p><strong>Phản hồi:</strong> <span id="popupFeedback"></span></p>
        <button onclick="hidePopup()">Đóng</button>
    </div>

    <!-- Popup từ chối yêu cầu nạp tiền -->
    <div class="popup-overlay" id="rejectOverlay" onclick="hideRejectPopup()"></div>
    <div class="popup" id="rejectPopup">
        <h3>Từ chối yêu cầu của <span id="rejectUserName"></span></h3>
        <form id="rejectForm" method="POST" action="">
            @csrf
            <input type="hidden" name="request_id" id="rejectRequestId">
            <p><strong>Lý do từ chối:</strong></p>
            <textarea name="reason" id="rejectReason" rows="4" placeholder="Nhập lý do từ chối..." required></textarea>
            <button type="submit">Gửi</button>
        </form>
    </div>

    <!-- Popup thêm món đồ gốm mới -->
    <div class="popup-overlay" id="addCeramicOverlay" onclick="hideAddCeramicPopup()"></div>
    <div class="popup" id="addCeramicPopup">
        <h3>Thêm Món Đồ Gốm Mới</h3>
        <form id="addCeramicForm" method="POST" action="{{ route('admin.ceramics.store') }}">
            @csrf
            <p><strong>Tên:</strong></p>
            <input type="text" name="name" required placeholder="Nhập tên món đồ gốm">
            <p><strong>Mô tả:</strong></p>
            <textarea name="description" rows="4" placeholder="Nhập mô tả (tùy chọn)"></textarea>
            <p><strong>Hình ảnh:</strong></p>
            <input type="text" name="image" placeholder="Đường dẫn hình ảnh (ceramics/ten_hinh.jpg)">
            <p><strong>Danh mục:</strong></p>
            <input type="text" name="category" placeholder="Nhập danh mục (tùy chọn)">
            <p><strong>Nguồn gốc:</strong></p>
            <input type="text" name="origin" placeholder="Nhập nguồn gốc (tùy chọn)">
            <button type="submit">Thêm</button>
        </form>
    </div>

    <script>
        // Tab switching
        document.querySelectorAll('.sidebar a').forEach(link => {
            link.addEventListener('click', function(e) {
                if (!this.href.includes('logout')) {
                    e.preventDefault();
                    document.querySelectorAll('.sidebar a').forEach(a => a.classList.remove('active'));
                    this.classList.add('active');
                    document.querySelectorAll('.tab-content').forEach(tab => tab.style.display = 'none');
                    const tabContent = document.getElementById(this.dataset.tab);
                    if (tabContent) {
                        tabContent.style.display = 'block';
                        if (this.dataset.tab === 'revenue') {
                            renderRevenueChart();
                        }
                    }
                }
            });
        });

        // Edit user row
// Biến để lưu giá trị ban đầu của các trường
let initialValues = {};

// Edit user row
function editRow(userId) {
    const row = document.getElementById(`row-${userId}`);
    const editables = row.querySelectorAll('.editable');
    const editBtn = row.querySelector('.edit-btn');
    const saveBtn = row.querySelector('.save-btn');
    const cancelBtn = row.querySelector('.cancel-btn');

    // Lưu giá trị ban đầu của các trường
    initialValues[userId] = {};
    editables.forEach(cell => {
        const field = cell.dataset.field;
        const input = cell.querySelector('input, select');
        initialValues[userId][field] = input.value;
    });

    editables.forEach(cell => {
        const display = cell.querySelector('.display');
        const input = cell.querySelector('input, select');
        display.style.display = 'none';
        input.style.display = 'block';
    });

    editBtn.style.display = 'none';
    saveBtn.style.display = 'inline-flex';
    cancelBtn.style.display = 'inline-flex';
}

function cancelEdit(userId) {
    const row = document.getElementById(`row-${userId}`);
    const editables = row.querySelectorAll('.editable');
    const editBtn = row.querySelector('.edit-btn');
    const saveBtn = row.querySelector('.save-btn');
    const cancelBtn = row.querySelector('.cancel-btn');

    editables.forEach(cell => {
        const display = cell.querySelector('.display');
        const input = cell.querySelector('input, select');
        input.style.display = 'none';
        display.style.display = 'block';
        // Khôi phục giá trị ban đầu
        input.value = initialValues[userId][cell.dataset.field];
    });

    editBtn.style.display = 'inline-flex';
    saveBtn.style.display = 'none';
    cancelBtn.style.display = 'none';

    // Xóa giá trị ban đầu khi hủy
    delete initialValues[userId];
}

// Kiểm tra thay đổi trước khi gửi form
document.querySelectorAll('.edit-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault(); // Ngăn gửi form mặc định để kiểm tra

        const userId = this.id.replace('form-', '');
        const editables = document.getElementById(`row-${userId}`).querySelectorAll('.editable');
        let hasChanges = false;

        // Xóa các hidden input cũ (nếu có) để tránh trùng lặp
        const existingHiddenInputs = form.querySelectorAll('input[type="hidden"]:not([name="_token"]):not([name="_method"])');
        existingHiddenInputs.forEach(input => input.remove());

        // Thêm các trường ẩn vào form để gửi dữ liệu
        editables.forEach(cell => {
            const field = cell.dataset.field;
            const input = cell.querySelector('input, select');
            const currentValue = input.value;
            const initialValue = initialValues[userId][field];

            // Tạo input ẩn để gửi dữ liệu
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = field;
            hiddenInput.value = currentValue;
            form.appendChild(hiddenInput);

            // So sánh giá trị hiện tại với giá trị ban đầu
            if (currentValue !== initialValue) {
                hasChanges = true;
            }
        });

        // Nếu không có thay đổi, hiển thị thông báo và dừng
        if (!hasChanges) {
            alert('Không có thay đổi để lưu!');
            return;
        }

        // Kiểm tra dữ liệu gửi đi
        const formData = new FormData(this);
        console.log('Dữ liệu gửi đi:', Object.fromEntries(formData));

        // Gửi form
        form.submit();
    });
});




// Biến để lưu giá trị ban đầu của các trường trong tab Quản lý thư viện đồ gốm
let initialCeramicValues = {};

// Edit ceramic row
function editCeramicRow(ceramicId) {
    const row = document.getElementById(`ceramic-row-${ceramicId}`);
    const editables = row.querySelectorAll('.editable');
    const editBtn = row.querySelector('.edit-btn');
    const saveBtn = row.querySelector('.save-btn');
    const cancelBtn = row.querySelector('.cancel-btn');

    // Lưu giá trị ban đầu của các trường
    initialCeramicValues[ceramicId] = {};
    editables.forEach(cell => {
        const field = cell.dataset.field;
        const input = cell.querySelector('input, textarea');
        initialCeramicValues[ceramicId][field] = input.value;
    });

    editables.forEach(cell => {
        const display = cell.querySelector('.display');
        const input = cell.querySelector('input, textarea');
        display.style.display = 'none';
        input.style.display = 'block';
    });

    editBtn.style.display = 'none';
    saveBtn.style.display = 'inline-flex';
    cancelBtn.style.display = 'inline-flex';
}

function cancelCeramicEdit(ceramicId) {
    const row = document.getElementById(`ceramic-row-${ceramicId}`);
    const editables = row.querySelectorAll('.editable');
    const editBtn = row.querySelector('.edit-btn');
    const saveBtn = row.querySelector('.save-btn');
    const cancelBtn = row.querySelector('.cancel-btn');

    editables.forEach(cell => {
        const display = cell.querySelector('.display');
        const input = cell.querySelector('input, textarea');
        input.style.display = 'none';
        display.style.display = 'block';
        // Khôi phục giá trị ban đầu
        input.value = initialCeramicValues[ceramicId][cell.dataset.field];
    });

    editBtn.style.display = 'inline-flex';
    saveBtn.style.display = 'none';
    cancelBtn.style.display = 'none';

    // Xóa giá trị ban đầu khi hủy
    delete initialCeramicValues[ceramicId];
}

// Xử lý gửi form trong tab Quản lý thư viện đồ gốm
document.querySelectorAll('#ceramics .edit-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault(); // Ngăn gửi form mặc định để kiểm tra

        const ceramicId = this.id.replace('ceramic-form-', '');
        const editables = document.getElementById(`ceramic-row-${ceramicId}`).querySelectorAll('.editable');
        let hasChanges = false;

        // Xóa các hidden input cũ (nếu có) để tránh trùng lặp
        const existingHiddenInputs = form.querySelectorAll('input[type="hidden"]:not([name="_token"]):not([name="_method"])');
        existingHiddenInputs.forEach(input => input.remove());

        // Thêm các trường ẩn vào form để gửi dữ liệu
        editables.forEach(cell => {
            const field = cell.dataset.field;
            const input = cell.querySelector('input, textarea');
            const currentValue = input.value;
            const initialValue = initialCeramicValues[ceramicId][field];

            // Tạo input ẩn để gửi dữ liệu
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = field;
            hiddenInput.value = currentValue;
            form.appendChild(hiddenInput);

            // So sánh giá trị hiện tại với giá trị ban đầu
            if (currentValue !== initialValue) {
                hasChanges = true;
            }
        });

        // Nếu không có thay đổi, hiển thị thông báo và dừng
        if (!hasChanges) {
            alert('Không có thay đổi để lưu!');
            return;
        }

        // Kiểm tra dữ liệu gửi đi
        const formData = new FormData(this);
        console.log('Dữ liệu gửi đi:', Object.fromEntries(formData));

        // Gửi form
        form.submit();
    });

    // Ngăn chặn gửi form khi nhấn Enter trong khi chỉnh sửa
    form.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault(); // Ngăn chặn hành vi mặc định của Enter
        }
    });
});

        // Show/Hide Add Ceramic Popup
        function showAddCeramicPopup() {
            const popup = document.getElementById('addCeramicPopup');
            const overlay = document.getElementById('addCeramicOverlay');
            popup.style.display = 'block';
            overlay.style.display = 'block';
        }

        function hideAddCeramicPopup() {
            const popup = document.getElementById('addCeramicPopup');
            const overlay = document.getElementById('addCeramicOverlay');
            popup.style.display = 'none';
            overlay.style.display = 'none';
        }

        // Popup thông tin đánh giá
        function showPopup(userId, name, rating, feedback) {
            const popup = document.getElementById('userPopup');
            const overlay = document.querySelector('.popup-overlay');
            const popupName = document.getElementById('popupName');
            const popupRating = document.getElementById('popupRating');
            const popupFeedback = document.getElementById('popupFeedback');

            popupName.textContent = name;
            popupFeedback.textContent = feedback;

            popupRating.innerHTML = '';
            for (let i = 1; i <= 5; i++) {
                const star = document.createElement('i');
                star.classList.add('fa-star', i <= rating ? 'fas' : 'far');
                popupRating.appendChild(star);
            }

            popup.style.display = 'block';
            overlay.style.display = 'block';
        }

        function hidePopup() {
            const popup = document.getElementById('userPopup');
            const overlay = document.querySelector('.popup-overlay');
            popup.style.display = 'none';
            overlay.style.display = 'none';
        }

        // Popup từ chối yêu cầu
        function showRejectPopup(requestId, userName) {
            const popup = document.getElementById('rejectPopup');
            const overlay = document.getElementById('rejectOverlay');
            const rejectUserName = document.getElementById('rejectUserName');
            const rejectRequestId = document.getElementById('rejectRequestId');
            const form = document.getElementById('rejectForm');

            rejectUserName.textContent = userName;
            rejectRequestId.value = requestId;
            form.action = '{{ route("admin.recharge.reject") }}';

            popup.style.display = 'block';
            overlay.style.display = 'block';
        }

        function hideRejectPopup() {
            const popup = document.getElementById('rejectPopup');
            const overlay = document.getElementById('rejectOverlay');
            popup.style.display = 'none';
            overlay.style.display = 'none';
        }

        // Revenue Chart
        function renderRevenueChart() {
            const ctx = document.getElementById('revenueChart');
            if (!ctx) {
                console.error('Không tìm thấy canvas #revenueChart');
                return;
            }

            const labels = {!! json_encode($revenueLabels) !!} || ['Chưa có dữ liệu'];
            const data = {!! json_encode($revenueData) !!} || [0];

            console.log('Revenue Labels:', labels);
            console.log('Revenue Data:', data);

            if (window.revenueChart && typeof window.revenueChart.destroy === 'function') {
                window.revenueChart.destroy();
            }

            window.revenueChart = new Chart(ctx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Doanh thu (VNĐ)',
                        data: data,
                        borderColor: '#1e88e5',
                        backgroundColor: 'rgba(30, 136, 229, 0.2)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { callback: value => value.toLocaleString('vi-VN') + ' VNĐ' }
                        }
                    },
                    plugins: {
                        legend: { display: true },
                        tooltip: { callbacks: { label: context => context.parsed.y.toLocaleString('vi-VN') + ' VNĐ' } }
                    }
                }
            });
        }
        // Ngăn chặn gửi form khi nhấn Enter trong khi chỉnh sửa
        document.querySelectorAll('.edit-form').forEach(form => {
            form.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault(); // Ngăn chặn hành vi mặc định của Enter
                }
            });
        });

        // Gọi lần đầu khi trang tải
        document.addEventListener('DOMContentLoaded', function() {
            const activeTab = document.querySelector('.sidebar a.active').dataset.tab;
            if (activeTab === 'overview') {
                document.getElementById('overview').style.display = 'block';
            }
        });




    //Lịch sử nhận diện
    
    

    // Dữ liệu lịch sử nhận diện (giả lập từ PHP)
const classifications = @json($classifications);

// Hiển thị lịch sử nhận diện của người dùng
function showClassificationHistory(userId, userName) {
    const popup = document.getElementById('classificationPopup');
    const overlay = document.getElementById('classificationOverlay');
    const userNameElement = document.getElementById('classificationUserName');
    const historyTable = document.getElementById('classificationHistoryTable');

    // Hiển thị tên người dùng
    userNameElement.textContent = userName;

    // Lọc lịch sử nhận diện của người dùng
    const userClassifications = classifications.filter(item => item.user_id == userId);

    // Xóa nội dung cũ
    historyTable.innerHTML = '';

    // Nếu không có lịch sử
    if (userClassifications.length === 0) {
        historyTable.innerHTML = '<tr><td colspan="4">Không có lịch sử nhận diện.</td></tr>';
    } else {
        // Thêm các dòng lịch sử
        userClassifications.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${item.id}</td>
                <td><img src="${item.image_path}" alt="Image"></td>
                <td>${item.result}</td>
                <td>${new Date(item.created_at).toLocaleString('vi-VN')}</td>
            `;
            historyTable.appendChild(row);
        });
    }

    // Hiển thị popup
    popup.style.display = 'block';
    overlay.style.display = 'block';
}

// Ẩn popup lịch sử nhận diện
function hideClassificationHistory() {
    const popup = document.getElementById('classificationPopup');
    const overlay = document.getElementById('classificationOverlay');
    popup.style.display = 'none';
    overlay.style.display = 'none';
}



//lịch sử đăng nhập


// Dữ liệu lịch sử đăng nhập (giả lập từ PHP)
const loginHistories = @json($users->mapWithKeys(function ($user) {
    return [$user->id => $user->loginHistories];
})->toArray());

// Hiển thị lịch sử đăng nhập của người dùng
function showLoginHistory(userId, userName) {
    const popup = document.getElementById('loginHistoryPopup');
    const overlay = document.getElementById('loginHistoryOverlay');
    const userNameElement = document.getElementById('loginHistoryUserName');
    const historyTable = document.getElementById('loginHistoryTable');

    // Hiển thị tên người dùng
    userNameElement.textContent = userName;

    // Lấy lịch sử đăng nhập của người dùng
    const userLoginHistories = loginHistories[userId] || [];

    // Xóa nội dung cũ
    historyTable.innerHTML = '';

    // Nếu không có lịch sử
    if (userLoginHistories.length === 0) {
        historyTable.innerHTML = '<tr><td colspan="3">Không có lịch sử đăng nhập.</td></tr>';
    } else {
        // Thêm các dòng lịch sử
        userLoginHistories.forEach(item => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${new Date(item.login_time).toLocaleString('vi-VN')}</td>
                <td>${item.ip_address || 'Không có'}</td>
                <td>${item.device_info || 'Không có'}</td>
            `;
            historyTable.appendChild(row);
        });
    }

    // Hiển thị popup
    popup.style.display = 'block';
    overlay.style.display = 'block';
}

// Ẩn popup lịch sử đăng nhập
function hideLoginHistory() {
    const popup = document.getElementById('loginHistoryPopup');
    const overlay = document.getElementById('loginHistoryOverlay');
    popup.style.display = 'none';
    overlay.style.display = 'none';
}



//Bật tắt capcha
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('captchaForm');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Hiển thị loading
        const btn = this.querySelector('button[type="submit"]');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang lưu...';
        btn.disabled = true;
        
        fetch(this.action, {
            method: 'POST',
            body: new FormData(this),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert('Cập nhật thành công!');
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            btn.innerHTML = '<i class="fas fa-save"></i> Lưu thay đổi';
            btn.disabled = false;
        });
    });
});
    </script>
</body>
</html>