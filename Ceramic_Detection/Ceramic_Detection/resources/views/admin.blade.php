<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Quản Lý Người Dùng</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.min.js"></script>
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
        </div>

        <!-- Tab Quản lý người dùng -->
        <div class="container tab-content" id="users" style="display: none;">
            <h1>Quản Lý Người Dùng</h1>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Vai trò</th>
                        <th>Tokens</th>
                        <th>Hành động</th>
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
                            <td class="actions">
                                <button type="button" class="action-btn edit-btn" onclick="editRow({{ $user->id }})"><i class="fas fa-edit"></i> Sửa</button>
                                <form action="{{ route('admin.update', $user->id) }}" method="POST" class="edit-form" id="form-{{ $user->id }}">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="action-btn save-btn" style="display:none;"><i class="fas fa-save"></i> Lưu</button>
                                </form>
                                <button type="button" class="action-btn cancel-btn" style="display:none;" onclick="cancelEdit({{ $user->id }})"><i class="fas fa-times"></i> Hủy</button>
                                <form action="{{ route('admin.delete', $user->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc muốn xóa người dùng này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn delete-btn"><i class="fas fa-trash"></i> Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
        function editRow(userId) {
            const row = document.getElementById(`row-${userId}`);
            const editables = row.querySelectorAll('.editable');
            const editBtn = row.querySelector('.edit-btn');
            const saveBtn = row.querySelector('.save-btn');
            const cancelBtn = row.querySelector('.cancel-btn');

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
            });

            editBtn.style.display = 'inline-flex';
            saveBtn.style.display = 'none';
            cancelBtn.style.display = 'none';
        }

        // Edit ceramic row
        function editCeramicRow(ceramicId) {
            const row = document.getElementById(`ceramic-row-${ceramicId}`);
            const editables = row.querySelectorAll('.editable');
            const editBtn = row.querySelector('.edit-btn');
            const saveBtn = row.querySelector('.save-btn');
            const cancelBtn = row.querySelector('.cancel-btn');

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
            });

            editBtn.style.display = 'inline-flex';
            saveBtn.style.display = 'none';
            cancelBtn.style.display = 'none';
        }

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
    </script>
</body>
</html>