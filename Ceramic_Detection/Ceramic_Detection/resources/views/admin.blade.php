<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Quản Lý Người Dùng</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
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
            width: 285px;
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

        input#userSearch {
            width: 300px;
            height: 45px;
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

        .sidebar a:hover,
        .sidebar a.active {
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

        .stat-card canvas {
            width: 100% !important;
            height: 150px !important;
        }

        .stat-card {
            flex: 1;
            padding: 15px;
            background: var(--gradient);
            color: white;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            margin: 0 10px;
            min-width: 200px;
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

        @media (max-width: 900px) {
            .stat-card {
                flex: 0 0 48%;
                margin-bottom: 20px;
            }
        }

        @media (max-width: 600px) {
            .stat-card {
                flex: 0 0 100%;
            }
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
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

        .tab-content canvas {
            margin: 10px 0;
            max-width: 100%;
            max-height: 300px;
            /* Đảm bảo không vượt quá container */
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

        .edit-btn {
            background: var(--warning-color);
            color: var(--dark-color);
        }

        .edit-btn:hover {
            background: #ffb300;
            transform: translateY(-2px);
        }

        .save-btn {
            background: var(--success-color);
            color: white;
        }

        .save-btn:hover {
            background: #00a843;
            transform: translateY(-2px);
        }

        .cancel-btn {
            background: #ccc;
            color: var(--dark-color);
        }

        .cancel-btn:hover {
            background: #b0b0b0;
            transform: translateY(-2px);
        }

        .delete-btn {
            background: var(--error-color);
            color: white;
        }

        .delete-btn:hover {
            background: #d32f2f;
            transform: translateY(-2px);
        }

        .reject-btn {
            background: #ff5722;
            color: white;
        }

        .reject-btn:hover {
            background: #e64a19;
            transform: translateY(-2px);
        }

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
            width: 1000px;
            height: 700px;
            overflow-y: auto;
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

        .popup textarea,
        .popup input {
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
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* Định dạng bảng trong tab Quản lý thư viện đồ gốm */
        #ceramics table {
            table-layout: fixed;
            /* Đảm bảo các cột có độ rộng cố định */
            width: 100%;
        }

        /* Đặt độ rộng cố định cho các cột */
        #ceramics th,
        #ceramics td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid var(--accent-color);
            word-wrap: break-word;
            /* Đảm bảo nội dung dài không tràn */
            overflow: hidden;
            text-overflow: ellipsis;
            /* Thêm dấu ... khi nội dung bị cắt */
        }

        /* Đặt độ rộng cụ thể cho từng cột */
        #ceramics th:nth-child(1),
        #ceramics td:nth-child(1) {
            width: 5%;
        }

        /* ID */
        #ceramics th:nth-child(2),
        #ceramics td:nth-child(2) {
            width: 15%;
        }

        /* Tên */
        #ceramics th:nth-child(3),
        #ceramics td:nth-child(3) {
            width: 25%;
        }

        /* Mô tả */
        #ceramics th:nth-child(4),
        #ceramics td:nth-child(4) {
            width: 15%;
        }

        /* Hình ảnh */
        #ceramics th:nth-child(5),
        #ceramics td:nth-child(5) {
            width: 15%;
        }

        /* Danh mục */
        #ceramics th:nth-child(6),
        #ceramics td:nth-child(6) {
            width: 15%;
        }

        /* Nguồn gốc */
        #ceramics th:nth-child(7),
        #ceramics td:nth-child(7) {
            width: 10%;
        }

        /* Hành động */
        /* Giới hạn chiều cao và ẩn nội dung dài trong cột Mô tả */
        #ceramics .description-cell {
            max-height: 3em;
            /* Giới hạn chiều cao (khoảng 3 dòng) */
            overflow: hidden;
            position: relative;
            line-height: 1.5em;
            /* Đảm bảo chiều cao dòng phù hợp */
            display: -webkit-box;
            -webkit-line-clamp: 2;
            /* Giới hạn số dòng hiển thị */
            -webkit-box-orient: vertical;
        }

        /* Hiển thị toàn bộ nội dung khi có class expanded */
        #ceramics .description-cell.expanded {
            max-height: none;
            -webkit-line-clamp: unset;
        }

        /* Định dạng nút Xem thêm */
        #ceramics .toggle-description {
            color: var(--secondary-color);
            cursor: pointer;
            font-size: 0.9rem;
            margin-top: 5px;
            display: inline-block;
            text-decoration: underline;
        }

        #ceramics .toggle-description:hover {
            color: var(--dark-color);
        }

        /* Đảm bảo hình ảnh không vượt quá kích thước cột */
        #ceramics .image-cell img {
            max-width: 100%;
            height: auto;
            display: block;
        }

        /* Ẩn nút Xem thêm khi đang chỉnh sửa */
        #ceramics .editable.editing .toggle-description {
            display: none;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 80px;
            }

            .sidebar a {
                font-size: 0;
            }

            .sidebar a i {
                margin-right: 0;
                font-size: 1.2rem;
            }

            .content {
                margin-left: 80px;
            }

            .stats {
                flex-direction: column;
            }

            table {
                font-size: 0.75rem;
            }
        }

        /* Thêm vào phần style */
        .action-btn.save-btn i {
            font-size: 0.9rem;
        }

        /* Lich Sử nhán diện .popup */
        /* Cải tiến Popup Lịch Sử Nhận Diện */
        #classificationPopup {
            width: 1000px;
            max-width: 95%;
            height: 700px;
            overflow-y: auto;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        #classificationPopup h3 {
            font-size: 1.6rem;
            color: var(--primary-color);
            margin-bottom: 20px;
            text-align: center;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        #classificationPopup table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        #classificationPopup th,
        #classificationPopup td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid var(--accent-color);
        }

        #classificationPopup th {
            background: var(--primary-color);
            color: white;
            font-weight: 500;
        }

        #classificationPopup tr:hover {
            background: var(--light-color);
        }

        #classificationPopup td img {
            max-width: 80px;
            height: auto;
            border-radius: 5px;
        }

        /* Giới hạn chiều cao và ẩn nội dung dài trong cột Thông Tin */
        #classificationPopup .info-cell {
            max-height: 3em;
            /* Giới hạn chiều cao (khoảng 3 dòng) */
            overflow: hidden;
            position: relative;
            line-height: 1.5em;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            /* Giới hạn số dòng hiển thị */
            -webkit-box-orient: vertical;
        }

        /* Hiển thị toàn bộ nội dung khi có class expanded */
        #classificationPopup .info-cell.expanded {
            max-height: none;
            -webkit-line-clamp: unset;
        }

        /* Định dạng nút Xem thêm */
        #classificationPopup .toggle-info {
            color: var(--secondary-color);
            cursor: pointer;
            font-size: 0.9rem;
            margin-top: 5px;
            display: inline-block;
            text-decoration: underline;
        }

        #classificationPopup .toggle-info:hover {
            color: var(--dark-color);
        }

        #classificationPopup button {
            background: var(--gradient);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 20px;
            display: block;
            margin-left: auto;
            margin-right: auto;
            transition: all 0.3s;
        }

        #classificationPopup button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        /* Dấu chấm xanh */
        .notification-dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            background-color: var(--success-color);
            /* Màu xanh lá cây */
            border-radius: 50%;
            margin-left: 8px;
            vertical-align: middle;
        }

        .menu .logout {
            margin-top: auto;
            /* đẩy nút xuống cuối */
        }

        /* Tab Tin tức */
        /* Định dạng bảng trong tab Quản lý tin tức */
        #news table {
            table-layout: fixed;
            width: 100%;
        }

        #news th:nth-child(1),
        #news td:nth-child(1) {
            width: 5%;
        }

        /* ID */
        #news th:nth-child(2),
        #news td:nth-child(2) {
            width: 20%;
        }

        /* Tiêu đề */
        #news th:nth-child(3),
        #news td:nth-child(3) {
            width: 20%;
        }

        /* Mô tả ngắn */
        #news th:nth-child(4),
        #news td:nth-child(4) {
            width: 15%;
        }

        /* Hình ảnh */
        #news th:nth-child(5),
        #news td:nth-child(5) {
            width: 25%;
        }

        /* Nội dung */
        #news th:nth-child(6),
        #news td:nth-child(6) {
            width: 15%;
        }

        /* Hành động */
        #news .description-cell {
            max-height: 3em;
            overflow: hidden;
            line-height: 1.5em;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        #news .description-cell.expanded {
            max-height: none;
            -webkit-line-clamp: unset;
        }

        #news .toggle-description {
            color: var(--secondary-color);
            cursor: pointer;
            font-size: 0.9rem;
            margin-top: 5px;
            display: inline-block;
            text-decoration: underline;
        }

        #news .toggle-description:hover {
            color: var(--dark-color);
        }

        #news .editable.editing .toggle-description {
            display: none;
        }

        div.sidebar {
            overflow-y: scroll;
        }

        .status.active {
            background: var(--success-color);
            color: white;
        }

        .status.inactive {
            background: var(--error-color);
            color: white;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .pagination a {
            padding: 8px 14px;
            background: var(--primary-color);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s;
        }

        .pagination a:hover {
            background: var(--secondary-color);
        }

        .pagination a.active {
            background: var(--success-color);
            font-weight: bold;
        }

        .pagination a.disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        #classificationHistoryContent {
            max-height: 526px;
            overflow-y: auto;
        }

        div#contacts.container.tab-content table tbody tr td span {
            background-color: greenyellow;
        }

        /* Thông tin hệ thống */
        /* Định dạng menu con */
        .system-info-menu {
            position: relative;
        }

        .submenu {
            position: absolute;
            left: 100%;
            top: 0;
            width: 150px;
            background: var(--dark-color);
            list-style: none;
            border-radius: 8px;
            padding: 10px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .submenu li {
            margin-bottom: 10px;
        }

        .submenu a {
            padding: 8px;
            font-size: 0.9rem;
        }

        .submenu a:hover {
            background: var(--gradient);
        }

        /* Hiển thị submenu khi hover */
        .system-info-menu:hover .submenu {
            display: block;
        }

        /* Định dạng tab nội dung */
        #system-info .tab-container {
            margin-top: 20px;
        }

        #system-info .tab-button {
            padding: 10px 20px;
            cursor: pointer;
            background: var(--accent-color);
            border-radius: 5px;
            margin-right: 10px;
        }

        #system-info .tab-button.active {
            background: var(--gradient);
            color: white;
        }

        #system-info .tab-content {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        /* Cảnh báo khẩn cấp */
        .alert-emergency {
            background-color: #ff3333;
            color: white;
            padding: 10px;
            margin-bottom: 20px;
            font-weight: bold;
            border-radius: 5px;
        }

        .stats-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h2>Quản Lý</h2>
        <ul>
            <li><a href="#" data-tab="overview" class="active"><i class="fas fa-tachometer-alt"></i> Tổng quan</a></li>
            <li><a href="#" data-tab="users"><i class="fas fa-users"></i> Quản lý người dùng</a></li>
            <li>
                <a href="#" data-tab="contacts">
                    <i class="fas fa-envelope"></i> Liên hệ
                    @if ($contacts->where('is_read', false)->isNotEmpty())
                        <span class="notification-dot"></span>
                    @endif
                </a>
            </li>
            <li>
                <a href="#" data-tab="recharge">
                    <i class="fas fa-money-bill"></i> Yêu cầu nạp tiền
                    @if ($rechargeRequests->isNotEmpty())
                        <span class="notification-dot"></span>
                    @endif
                </a>
            </li>
            <li><a href="#" data-tab="revenue"><i class="fas fa-chart-line"></i> Doanh thu</a></li>
            <li><a href="#" data-tab="ceramics"><i class="fa-solid fa-layer-group"></i> Quản lý thư viện đồ gốm</a></li>
            <li><a href="#" data-tab="news"><i class="fas fa-newspaper"></i> Quản lý tin tức</a></li>
            <li><a href="#" data-tab="classifications"><i class="fas fa-history"></i> Lịch Sử Nhận Diện</a></li>
            <li class="system-info-menu">
                <a href="#" data-tab="system-info"><i class="fas fa-server"></i> Thông tin hệ thống</a>
                <ul class="submenu" style="display: none;">
                    <li><a href="#" data-tab="system-info" data-subtab="fastapi">FastAPI</a></li>
                    <li><a href="#" data-tab="system-info" data-subtab="laravel">Laravel</a></li>
                </ul>
            </li>
            <li><a href="#" data-tab="terms"><i class="fas fa-file-alt"></i> Chính sách và điều khoản</a></li>
            <li><a href="#" data-tab="settings"><i class="fas fa-cog"></i> Cài Đặt</a></li>
            <li><a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                        class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
        </ul>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
    <div class="content">
        <!-- Tab Tổng quan -->
        <div class="container tab-content" id="overview">
            <<h1>Tổng Quan</h1>
                <!-- Hàng 1 -->
                <div class="stats-row">
                    <div class="stat-card">
                        <h3>Tổng người dùng</h3>
                        <p>{{ $users->count() }}</p>
                        <canvas id="userStatusPieChart"></canvas>
                    </div>
                    <div class="stat-card">
                        <h3>Yêu cầu chờ duyệt</h3>
                        <p>{{ $rechargeRequests->count() }}</p>
                        <canvas id="rechargeTrendChart"></canvas>
                    </div>
                    <div class="stat-card">
                        <h3>Tổng doanh thu</h3>
                        <p>{{ number_format($totalRevenue) }} VNĐ</p>
                        <canvas id="revenueTrendChart"></canvas>
                    </div>
                </div>
                <!-- Hàng 2 -->
                <div class="stats-row">
                    <div class="stat-card">
                        <h3>Trạng thái yêu cầu</h3>
                        <p> Duyệt: {{ $approvedRequests }} Hủy: {{ $rejectedRequests }}</p>
                        <canvas id="requestStatusPieChart"></canvas>
                    </div>
                    <div class="stat-card">
                        <h3>Đánh giá trung bình</h3>
                        <p>{{ number_format($averageRating, 1) }}/5</p>
                        <canvas id="ratingTrendChart"></canvas>
                    </div>
                    <div class="stat-card">
                        <h3>Tổng lượt nhận diện</h3>
                        <p>{{ number_format($totalTokenUsed) }}</p>
                        <canvas id="tokenTrendChart"></canvas>
                    </div>
                </div>
                <!-- Bảng lịch sử giao dịch -->
                <div class="transaction-history">
                    <h3>Lịch Sử Giao Dịch</h3>
                    <form action="{{ route('admin.export.transaction.history') }}" method="GET"
                        style="margin-bottom: 20px;">
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
                        <table id="transactionTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên người dùng</th>
                                    <th>Số tiền</th>
                                    <th>Tokens tương ứng</th>
                                    <th>Trạng thái</th>
                                    <th>Thời gian</th>
                                </tr>
                            </thead>
                            <tbody id="transactionBody">
                                <!-- Nội dung sẽ được xử lý bằng JavaScript -->
                            </tbody>
                        </table>
                        <div class="pagination" id="transactionPagination" style="text-align: center; margin-top: 20px;">
                            <!-- Phân trang sẽ được thêm bằng JavaScript -->
                        </div>
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
        <!-- Tab Liên hệ -->
        <div class="container tab-content" id="contacts" style="display: none;">
            <h2>Danh sách liên hệ từ người dùng</h2>
            @if (session('success'))
                <div class="success-message">{{ session('success') }}</div>
            @endif
            @if (!isset($contacts) || $contacts->isEmpty())
                <p>Chưa có liên hệ nào từ người dùng.</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Họ tên</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($contacts as $contact)
                            <tr
                                style="background-color: {{ $contact->is_read ? 'var(--card-bg)' : 'rgba(42, 92, 139, 0.1)' }}; 
                                                                                                                                                                                                                                                                                                                                                                    color: {{ $contact->is_read ? 'var(--text)' : 'var(--primary)' }};
                                                                                                                                                                                                                                                                                                                                                                    border-left: 4px solid {{ $contact->is_read ? 'transparent' : 'var(--secondary)' }}">
                                <td>{{ $contact->name }}</td>
                                <td>
                                    <span
                                        style="display: inline-block; 
                                                                                                                                                                                                                                                                                                                                                                            padding: 0.25rem 0.5rem;
                                                                                                                                                                                                                                                                                                                                                                            border-radius: 12px;
                                                                                                                                                                                                                                                                                                                                                                            background-color: {{ $contact->is_read ? 'var(--border)' : 'var(--secondary)' }};
                                                                                                                                                                                                                                                                                                                                                                            color: {{ $contact->is_read ? 'var(--text)' : 'var(--text-light)' }};
                                                                                                                                                                                                                                                                                                                                                                            font-size: 0.85rem;
                                                                                                                                                                                                                                                                                                                                                                            font-weight: 500;">
                                        {{ $contact->is_read ? 'Đã đọc' : 'Chưa đọc' }}
                                    </span>
                                </td>
                                <td>
                                    <button
                                        onclick="showContactPopup('{{ $contact->id }}', '{{ $contact->name }}', '{{ $contact->phone }}', '{{ $contact->email }}', '{{ $contact->message }}', '{{ $contact->is_read ? 'Đã đọc' : 'Chưa đọc' }}', {{ $contact->is_read ? 'true' : 'false' }})"
                                        class="action-btn view-btn"
                                        style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; background-color: var(--primary); color: var(--text-light); border-radius: 6px; border: none; cursor: pointer; transition: var(--transition);">
                                        <i class="fas fa-eye"></i> Xem
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
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
                <div
                    style="color: var(--error-color); background: #f8d7da; padding: 8px; border-radius: 4px; margin-bottom: 15px; text-align: center;">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="filter-search" style="margin-bottom: 20px;">
                <input type="text" id="userSearch" placeholder="  Tìm kiếm theo tên hoặc email..."
                    onkeyup="filterUsers()">
                <a id="roleFilter" onchange="filterUsers()">
                </a>
            </div>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Vai trò</th>
                        <th>Tokens</th>
                        <th>Tokens đã dùng</th>
                        <th>Trạng thái</th> <!-- New Column -->
                        <th>Hành động</th>
                        <th>Lịch Sử Đăng Nhập</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr id="row-{{ $user->id }}">
                            <td>{{ $user->id }}</td>
                            <td class="editable" data-field="name">
                                <span class="display user-name"
                                    onclick="showPopup('{{ $user->id }}', '{{ $user->name }}', '{{ $user->rating ?? 0 }}', '{{ $user->feedback ?? 'Chưa có phản hồi' }}')">{{ $user->name }}</span>
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
                                <input type="number" name="tokens" value="{{ $user->tokens }}" style="display:none;"
                                    min="0">
                            </td>
                            <td>{{ $user->tokens_used }}</td>
                            <td class="editable" data-field="status"> <!-- New Status Field -->
                                <span
                                    class="display status {{ $user->status }}">{{ ucfirst($user->status === 'active' ? 'Hoạt động' : 'Không hoạt động') }}</span>
                                <select name="status" style="display:none;">
                                    <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>Hoạt động
                                    </option>
                                    <option value="inactive" {{ $user->status === 'inactive' ? 'selected' : '' }}>Không hoạt
                                        động</option>
                                </select>
                            </td>
                            <td class="actions">
                                <form action="{{ route('admin.update', $user->id) }}" method="POST" class="edit-form"
                                    id="form-{{ $user->id }}" style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <button type="button" class="action-btn edit-btn" onclick="editRow({{ $user->id }})"><i
                                            class="fas fa-edit"></i> Sửa</button>
                                    <button type="submit" class="action-btn save-btn" style="display:none;"><i
                                            class="fas fa-save"></i> Lưu</button>
                                    <button type="button" class="action-btn cancel-btn" style="display:none;"
                                        onclick="cancelEdit({{ $user->id }})"><i class="fas fa-times"></i> Hủy</button>
                                </form>
                                <form action="{{ route('admin.delete', $user->id) }}" method="POST" style="display:inline;"
                                    onsubmit="return confirm('Bạn có chắc muốn xóa người dùng này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn delete-btn"><i class="fas fa-trash"></i>
                                        Xóa</button>
                                </form>
                            </td>
                            <td>
                                <button class="action-btn save-btn"
                                    onclick="showLoginHistory('{{ $user->id }}', '{{ $user->name }}')">
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
                            <th>Tokens tương ứng</th>
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
                                            <img src="{{ url('/storage/' . $request->proof_image) }}" alt="Proof"
                                                style="max-width: 100px; border-radius: 5px;">
                                        </a>
                                    @else
                                        <p>Không có ảnh</p>
                                    @endif
                                </td>
                                <td class="actions">
                                    <form action="{{ route('admin.recharge.approve', $request->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        <button type="submit" class="action-btn save-btn"><i class="fas fa-check"></i> Xác
                                            nhận</button>
                                    </form>
                                    <button type="button" class="action-btn reject-btn"
                                        onclick="showRejectPopup('{{ $request->id }}', '{{ $request->user->name }}')"><i
                                            class="fas fa-times"></i> Không xác nhận</button>
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
            <button type="button" class="action-btn save-btn" onclick="showAddCeramicPopup()"
                style="margin-bottom: 20px;">
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
                                <form action="{{ route('admin.ceramics.update', $ceramic->id) }}" method="POST"
                                    class="edit-form" id="ceramic-form-{{ $ceramic->id }}">
                                    @csrf
                                    @method('PUT')
                                    <td>{{ $ceramic->id }}</td>
                                    <td class="editable" data-field="name">
                                        <span class="display">{{ $ceramic->name }}</span>
                                        <input type="text" name="name" value="{{ $ceramic->name }}" style="display:none;">
                                    </td>
                                    <td class="editable" data-field="description">
                                        <span class="display description-cell"
                                            id="description-{{ $ceramic->id }}">{{ $ceramic->description ?? 'Không có' }}</span>
                                        <span class="toggle-description" onclick="toggleDescription('{{ $ceramic->id }}')"
                                            id="toggle-{{ $ceramic->id }}">Xem thêm</span>
                                        <textarea name="description"
                                            style="display:none;">{{ $ceramic->description }}</textarea>
                                    </td>
                                    <td class="editable image-cell" data-field="image">
                                        <span class="display">
                                            @if ($ceramic->image)
                                                <img src="{{ url('/storage/' . $ceramic->image) }}" alt="{{ $ceramic->name }}"
                                                    style="max-width: 100px; border-radius: 5px;">
                                            @else
                                                Không có ảnh
                                            @endif
                                        </span>
                                        <input type="text" name="image" value="{{ $ceramic->image }}" style="display:none;"
                                            placeholder="Đường dẫn hình ảnh (ceramics/ten_hinh.jpg)">
                                    </td>
                                    <td class="editable" data-field="category">
                                        <span class="display">{{ $ceramic->category ?? 'Không có' }}</span>
                                        <input type="text" name="category" value="{{ $ceramic->category }}"
                                            style="display:none;">
                                    </td>
                                    <td class="editable" data-field="origin">
                                        <span class="display">{{ $ceramic->origin ?? 'Không có' }}</span>
                                        <input type="text" name="origin" value="{{ $ceramic->origin }}" style="display:none;">
                                    </td>
                                    <td class="actions">
                                        <button type="button" class="action-btn edit-btn"
                                            onclick="editCeramicRow({{ $ceramic->id }})"><i class="fas fa-edit"></i>
                                            Sửa</button>
                                        <form action="{{ route('admin.ceramics.update', $user->id) }}" method="POST"
                                            class="edit-form" id="form-{{ $user->id }}">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="action-btn save-btn" style="display:none;"><i
                                                    class="fas fa-save"></i> Lưu</button>
                                        </form>
                                        <button type="button" class="action-btn cancel-btn" style="display:none;"
                                            onclick="cancelCeramicEdit({{ $ceramic->id }})"><i class="fas fa-times"></i>
                                            Hủy</button>
                                        <form action="{{ route('admin.ceramics.delete', $ceramic->id) }}" method="POST"
                                            style="display:inline;"
                                            onsubmit="return confirm('Bạn có chắc muốn xóa món đồ gốm này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn delete-btn"><i class="fas fa-trash"></i>
                                                Xóa</button>
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
            <h3>Sao Lưu Dữ Liệu</h3>
            @if (session('backup_success'))
                <div class="success-message">
                    <!-- Ngay dưới phần success message -->
                    @if (session('error'))
                        <div
                            style="color: var(--error-color); background: #f8d7da; padding: 8px; border-radius: 4px; margin-bottom: 15px; text-align: center;">
                            {{ session('error') }}
                        </div>
                    @endif
                    {{ session('backup_success') }}
                </div>
            @endif
            <form method="POST" action="{{ route('admin.backup') }}">
                @csrf
                <p>Sao lưu toàn bộ cơ sở dữ liệu thành tệp SQL.</p>
                <button type="submit" class="action-btn save-btn">
                    <i class="fas fa-download"></i> Sao Lưu Dữ Liệu
                </button>
            </form>
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
                        <option value="UTC" {{ isset($currentTimezone) && $currentTimezone === 'UTC' ? 'selected' : '' }}>
                            UTC</option>
                        <option value="Asia/Ho_Chi_Minh" {{ isset($currentTimezone) && $currentTimezone === 'Asia/Ho_Chi_Minh' ? 'selected' : '' }}>Asia/Ho_Chi_Minh (Việt Nam,
                            GMT+7)</option>
                        <option value="Asia/Bangkok" {{ isset($currentTimezone) && $currentTimezone === 'Asia/Bangkok' ? 'selected' : '' }}>Asia/Bangkok (Thái Lan, GMT+7)</option>
                        <option value="Asia/Tokyo" {{ isset($currentTimezone) && $currentTimezone === 'Asia/Tokyo' ? 'selected' : '' }}>Asia/Tokyo (Nhật Bản, GMT+9)</option>
                        <option value="America/New_York" {{ isset($currentTimezone) && $currentTimezone === 'America/New_York' ? 'selected' : '' }}>America/New_York (Mỹ, GMT-5)
                        </option>
                        <option value="Europe/London" {{ isset($currentTimezone) && $currentTimezone === 'Europe/London' ? 'selected' : '' }}>Europe/London (Anh, GMT+0)</option>
                    </select>
                </div>
                <button type="submit" class="action-btn save-btn"><i class="fas fa-save"></i> Lưu Múi Giờ</button>
            </form>
            <!-- Thêm vào dưới phần CAPTCHA hoặc bất kỳ đâu trong tab settings -->
            <h3>Chọn Giao diện Trang Chủ</h3>
            @if (session('theme_success'))
                <div class="success-message">
                    {{ session('theme_success') }}
                </div>
            @endif
            <form method="POST" action="{{ route('admin.settings.theme') }}">
                @csrf
                <div>
                    <label>
                        <input type="radio" name="theme" value="index" {{ $currentTheme === 'index' ? 'checked' : '' }}>
                        Giao diện 1 (Mặc định)
                    </label>
                    <label>
                        <input type="radio" name="theme" value="index2" {{ $currentTheme === 'index2' ? 'checked' : '' }}>
                        Giao diện 2 (Hiện đại)
                    </label>
                </div>
                <button type="submit" class="action-btn save-btn"><i class="fas fa-save"></i> Lưu Giao Diện</button>
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
                            <td class="user-name"
                                onclick="showClassificationHistory('{{ $user->id }}', '{{ $user->name }}')">
                                {{ $user->name }}
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <button class="action-btn save-btn"
                                    onclick="showClassificationHistory('{{ $user->id }}', '{{ $user->name }}')">
                                    <i class="fas fa-eye"></i> Xem Lịch Sử
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
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
                    <textarea name="content" id="terms_content" rows="10"
                        required>{{ $terms ? $terms->content : '' }}</textarea>
                </div>
                <button type="submit" class="action-btn save-btn"><i class="fas fa-save"></i> Lưu</button>
            </form>
        </div>
        <!-- Tab Quản lý tin tức -->
        <div class="container tab-content" id="news" style="display: none;">
            <h1>Quản Lý Tin Tức</h1>
            <!-- Nút thêm bài viết mới -->
            <button type="button" class="action-btn save-btn" onclick="showAddNewsPopup()" style="margin-bottom: 20px;">
                <i class="fas fa-plus"></i> Thêm bài viết mới
            </button>
            <!-- Thông báo thành công (nếu có) -->
            @if (session('news_success'))
                <div class="success-message">
                    {{ session('news_success') }}
                </div>
            @endif
            <!-- Bảng danh sách tin tức -->
            @if ($news->isEmpty())
                <p>Không có bài viết tin tức nào.</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tiêu đề</th>
                            <th>Mô tả ngắn</th>
                            <th>Hình ảnh</th>
                            <th>Nội dung</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($news as $article)
                            <tr id="news-row-{{ $article->id }}">
                                <form action="{{ route('news.update', $article->id) }}" method="POST" class="edit-form"
                                    id="news-form-{{ $article->id }}">
                                    @csrf
                                    @method('PUT')
                                    <td>{{ $article->id }}</td>
                                    <td class="editable" data-field="title">
                                        <span class="display">{{ $article->title }}</span>
                                        <input type="text" name="title" value="{{ $article->title }}" style="display:none;">
                                    </td>
                                    <td class="editable" data-field="excerpt">
                                        <span class="display">{{ $article->excerpt ?? 'Không có' }}</span>
                                        <input type="text" name="excerpt" value="{{ $article->excerpt }}" style="display:none;">
                                    </td>
                                    <td class="editable image-cell" data-field="image">
                                        <span class="display">
                                            @if ($article->image)
                                                <img src="{{ url('/storage/' . $article->image) }}" alt="{{ $article->title }}"
                                                    style="max-width: 100px; border-radius: 5px;">
                                            @else
                                                Không có ảnh
                                            @endif
                                        </span>
                                        <input type="text" name="image" value="{{ $article->image }}" style="display:none;"
                                            placeholder="Đường dẫn hình ảnh (news/ten_hinh.jpg)">
                                    </td>
                                    <td class="editable" data-field="content">
                                        <span class="display description-cell"
                                            id="content-{{ $article->id }}">{{ $article->content ?? 'Không có' }}</span>
                                        <span class="toggle-description" onclick="toggleNewsContent('{{ $article->id }}')"
                                            id="toggle-content-{{ $article->id }}">Xem thêm</span>
                                        <textarea name="content" style="display:none;">{{ $article->content }}</textarea>
                                    </td>
                                    <td class="actions">
                                        <button type="button" class="action-btn edit-btn"
                                            onclick="editNewsRow({{ $article->id }})"><i class="fas fa-edit"></i> Sửa</button>
                                        <button type="button" class="action-btn cancel-btn" style="display:none;"
                                            onclick="cancelNewsEdit({{ $article->id }})"><i class="fas fa-times"></i>
                                            Hủy</button>
                                        <form action="{{ route('news.update', $article->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="action-btn save-btn" style="display:none;"><i
                                                    class="fas fa-save"></i> Lưu</button>
                                        </form>
                                        <button type="button" class="action-btn cancel-btn" style="display:none;"
                                            onclick="cancelNewsEdit({{ $article->id }})"><i class="fas fa-times"></i>
                                            Hủy</button>
                                        <form action="{{ route('news.delete', $article->id) }}" method="POST"
                                            style="display:inline;"
                                            onsubmit="return confirm('Bạn có chắc muốn xóa bài viết này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn delete-btn"><i class="fas fa-trash"></i>
                                                Xóa</button>
                                        </form>
                                    </td>
                                </form>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        <!-- Tab Thông tin hệ thống -->
        <div class="container tab-content" id="system-info" style="display: none;">
            <h1>Thông tin hệ thống</h1>
            <!-- Hiển thị cảnh báo khẩn cấp -->
            @if(!empty($alerts))
                <div class="alert-emergency">
                    <h3>CẢNH BÁO KHẨN CẤP</h3>
                    <ul>
                        @foreach($alerts as $alert)
                            <li>{{ $alert }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div style="margin-bottom: 20px;">
                <h3>Tối ưu hóa thông tin hệ thống</h3>
                <form method="POST" action="{{ route('admin.toggle-optimization') }}">
                    @csrf
                    <label>
                        <input type="checkbox" name="optimization" {{ $isSystemInfoEnabled ? 'checked' : '' }}
                            onchange="this.form.submit()">
                        Bật thông tin hệ thống
                    </label>
                </form>
            </div>
            <div class="tab-container">
                <button class="tab-button active" onclick="openSubTab('fastapi')">FastAPI</button>
                <button class="tab-button" onclick="openSubTab('laravel')">Laravel</button>
                <!-- Tab FastAPI -->
                <div id="fastapi" class="tab-content active">
                    <h3>Tài nguyên FastAPI</h3>
                    @if($isSystemInfoEnabled)
                        <div>
                            <h2>Tổng quan FastAPI</h2>
                            <ul>
                                <li>CPU Usage: {{ $fastapistats['cpu_usage_percent'] ?? 0 }}%</li>
                                <li>RAM Total: {{ $fastapistats['ram_total_mb'] ?? 0 }} MB</li>
                                <li>RAM Used: {{ $fastapistats['ram_used_mb'] ?? 0 }} MB</li>
                                <li>RAM Usage: {{ $fastapistats['ram_usage_percent'] ?? 0 }}%</li>
                                <li>GPU Usage: {{ $fastapistats['gpu_usage_percent'] ?? 0 }}%</li>
                                <li>GPU Total: {{ $fastapistats['gpu_total_mb'] ?? 0 }} MB</li>
                                <li>GPU Used: {{ $fastapistats['gpu_used_mb'] ?? 0 }} MB</li>
                                @if(isset($fastapistats['error']))
                                    <li>Lỗi: {{ $fastapistats['error'] }}</li>
                                @endif
                            </ul>
                        </div>
                        <canvas id="fastApiRamChart" width="300" height="150"></canvas>
                        <canvas id="fastApiCpuChart" width="300" height="150"></canvas>
                        <canvas id="fastApiGpuChart" width="300" height="150"></canvas>
                    @else
                        <p>Thông tin hệ thống đã bị tắt để tối ưu hiệu suất.</p>
                    @endif
                </div>
                <!-- Tab Laravel -->
                <div id="laravel" class="tab-content" style="display: none;">
                    <h3>Tài nguyên Laravel</h3>
                    @if($isSystemInfoEnabled)
                        <div>
                            <h2>Tổng quan Laravel</h2>
                            <ul>
                                <li>Phiên bản PHP: {{ $laravelStats['php_version'] ?? 'N/A' }}</li>
                                <li>Phiên bản Laravel: {{ $laravelStats['laravel_version'] ?? 'N/A' }}</li>
                                <li>Phiên bản MySQL: {{ $laravelStats['mysql_version'] ?? 'N/A' }}</li>
                                <li>Uptime Server: {{ $laravelStats['server_uptime'] ?? 'N/A' }}</li>
                                <li>Dung lượng ổ cứng còn trống: {{ $laravelStats['disk_space'] ?? 0 }}%</li>
                            </ul>
                        </div>
                        <canvas id="laravelRamChart" width="300" height="150"></canvas>
                        <canvas id="laravelCpuChart" width="300" height="150"></canvas>
                        <canvas id="laravelGpuChart" width="300" height="150"></canvas>
                    @else
                        <p>Thông tin hệ thống đã bị tắt để tối ưu hiệu suất.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Popup Chi tiết Liên hệ -->
    <div class="popup-overlay" id="contactOverlay" onclick="hideContactPopup()"></div>
    <div class="popup" id="contactPopup">
        <h3>Chi tiết liên hệ từ <span id="contactName"></span></h3>
        <div id="contactDetails">
            <p><strong>Số điện thoại:</strong> <span id="contactPhone"></span></p>
            <p><strong>Email:</strong> <span id="contactEmail"></span></p>
            <p><strong>Nội dung:</strong> <span id="contactMessage"></span></p>
            <p><strong>Trạng thái:</strong> <span id="contactStatus"></span></p>
        </div>
        <button onclick="hideContactPopup()">Đóng</button>
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
                        <th>Thông Tin</th>
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
    <!-- Popup "Xem thêm" cho Thông Tin -->
    <div class="popup-overlay" id="llmResponseOverlay" onclick="hideLlmResponsePopup()"></div>
    <div class="popup" id="llmResponsePopup">
        <h3>Thông Tin Chi Tiết</h3>
        <div id="llmResponseContent" class="llm-response-content"></div>
        <button onclick="hideLlmResponsePopup()">Đóng</button>
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
    <!-- Popup thêm bài viết tin tức mới -->
    <div class="popup-overlay" id="addNewsOverlay" onclick="hideAddNewsPopup()"></div>
    <div class="popup" id="addNewsPopup">
        <h3>Thêm Bài Viết Tin Tức Mới</h3>
        <form id="addNewsForm" method="POST" action="{{ route('news.store') }}">
            @csrf
            <p><strong>Tiêu đề:</strong></p>
            <input type="text" name="title" required placeholder="Nhập tiêu đề bài viết">
            <p><strong>Mô tả ngắn:</strong></p>
            <input type="text" name="excerpt" placeholder="Nhập mô tả ngắn (tùy chọn)">
            <p><strong>Hình ảnh:</strong></p>
            <input type="text" name="image" placeholder="Đường dẫn hình ảnh (news/ten_hinh.jpg)">
            <p><strong>Nội dung:</strong></p>
            <textarea name="content" rows="6" placeholder="Nhập nội dung bài viết" required></textarea>
            <button type="submit">Thêm</button>
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
            link.addEventListener('click', function (e) {
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
                document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
                document.getElementById(this.getAttribute('href').substring(1)).classList.add('active');
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
            form.addEventListener('submit', function (e) {
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
                cell.classList.add('editing');
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
                cell.classList.remove('editing');
                const descriptionCell = cell.querySelector('.description-cell');
                if (descriptionCell) {
                    descriptionCell.classList.remove('expanded');
                    const toggleLink = cell.querySelector('.toggle-description');
                    toggleLink.textContent = 'Xem thêm';
                }
            });
            editBtn.style.display = 'inline-flex';
            saveBtn.style.display = 'none';
            cancelBtn.style.display = 'none';
            // Xóa giá trị ban đầu khi hủy
            delete initialCeramicValues[ceramicId];
        }
        // Xử lý gửi form trong tab Quản lý thư viện đồ gốm
        document.querySelectorAll('#ceramics .edit-form').forEach(form => {
            form.addEventListener('submit', function (e) {
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
            form.addEventListener('keydown', function (e) {
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
        function toggleDescription(ceramicId) {
            const descriptionCell = document.getElementById(`description-${ceramicId}`);
            const toggleLink = document.getElementById(`toggle-${ceramicId}`);
            if (descriptionCell.classList.contains('expanded')) {
                descriptionCell.classList.remove('expanded');
                toggleLink.textContent = 'Xem thêm';
            } else {
                descriptionCell.classList.add('expanded');
                toggleLink.textContent = 'Ẩn bớt';
            }
        }
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
            form.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault(); // Ngăn chặn hành vi mặc định của Enter
                }
            });
        });
        // Gọi lần đầu khi trang tải
        document.addEventListener('DOMContentLoaded', function () {
            // Dữ liệu từ PHP
            const userTrend = @json($userTrend);
            const rechargeTrend = @json($rechargeTrend);
            const revenueTrend = @json($revenueTrend);
            const ratingTrend = @json($ratingTrend);
            const approvedRequests = @json($approvedRequests);
            const rejectedRequests = @json($rejectedRequests);
            const pendingRequests = @json($rechargeRequests->count());
            const activeUsers = @json($activeUsers);
            const inactiveUsers = @json($inactiveUsers);
            const revenueLabels = @json($revenueLabels); // Dữ liệu ngày
            const revenueData = @json($revenueData);
            const tokenTrend = @json($tokenTrend);
            // Hàm tạo biểu đồ
            function createChart(canvasId, labels, values, label, color, isCurrency = false) {
                const ctx = document.getElementById(canvasId);
                // Đặt màu fill là đen (nếu cần)
                if (!ctx) return;
                new Chart(ctx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: label,
                            data: values,
                            borderColor: color,
                            backgroundColor: `${color}70`, // Màu nền mờ
                            fill: true,
                            tension: 0.4,
                            pointRadius: 3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: value => isCurrency ? value.toLocaleString('vi-VN') + ' VNĐ' : value
                                }
                            },
                            x: { ticks: { font: { size: 10 } } }
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: context => `${context.dataset.label}: ${isCurrency ? context.parsed.y.toLocaleString('vi-VN') + ' VNĐ' : context.parsed.y}`
                                }
                            }
                        }
                    }
                });
            }
            // Hàm tạo biểu đồ tròn
            function createPieChart(canvasId, data, labels, colors) {
                const ctx = document.getElementById(canvasId);
                if (!ctx) return;
                new Chart(ctx.getContext('2d'), {
                    type: 'pie',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: data,
                            backgroundColor: colors,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom', labels: { font: { size: 10 } } },
                            tooltip: {
                                callbacks: {
                                    label: context => `${context.label}: ${context.raw}`
                                }
                            }
                        }
                    }
                });
            }
            // Vẽ các biểu đồ
            // createChart('userTrendChart', userTrend.labels, userTrend.values, 'Người dùng mới', '#42a5f5');
            createChart('tokenTrendChart', tokenTrend.labels, tokenTrend.values, 'Lượt nhận diện', '#ff5722');
            createChart('rechargeTrendChart', rechargeTrend.labels, rechargeTrend.values, 'Yêu cầu mới', '#ffca28');
            createChart('revenueTrendChart', revenueTrend.labels, revenueTrend.values, 'Doanh thu', '#00c853', true);
            createChart('ratingTrendChart', ratingTrend.labels, ratingTrend.values, 'Đánh giá', '#f44336');
            // Vẽ biểu đồ tròn cho Trạng thái yêu cầu
            createPieChart(
                'requestStatusPieChart',
                [approvedRequests, rejectedRequests],
                ['Đã duyệt', 'Đã hủy'],
                ['#42f5dd', 'yellow']
            );
            //Gọi renderTransactionHistory nếu có dữ liệu
            if (transactions.length > 0) {
                renderTransactionHistory(1);
            }
            // Vẽ biểu đồ tròn cho Tổng người dùng
            createPieChart(
                'userStatusPieChart',
                [activeUsers, inactiveUsers],
                ['Hoạt động', 'Không hoạt động'],
                ['#00c853', '#f44336']
            );
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
                historyTable.innerHTML = '<tr><td colspan="5">Không có lịch sử nhận diện.</td></tr>';
            } else {
                // Thêm các dòng lịch sử
                userClassifications.forEach(item => {
                    const infoText = item.llm_response || 'Không có thông tin';
                    const isLongInfo = infoText.length > 100; // Giới hạn độ dài để hiển thị "Xem thêm"
                    const row = document.createElement('tr');
                    row.innerHTML = `
                <td>${item.id}</td>
                <td><img src="${item.image_path}" alt="Image"></td>
                <td>${item.result}</td>
                <td>
                    <span class="info-cell" id="info-${item.id}">${infoText}</span>
                    ${isLongInfo ? `<span class="toggle-info" onclick="toggleInfo('${item.id}')">Xem thêm</span>` : ''}
                </td>
                <td>${new Date(item.created_at).toLocaleString('vi-VN')}</td>
            `;
                    historyTable.appendChild(row);
                });
            }
            // Hiển thị popup
            popup.style.display = 'block';
            overlay.style.display = 'block';
        }
        // Hàm toggleInfo để mở rộng/thu gọn nội dung
        function toggleInfo(id) {
            const infoCell = document.getElementById(`info-${id}`);
            const toggleLink = infoCell.nextElementSibling;
            if (infoCell.classList.contains('expanded')) {
                infoCell.classList.remove('expanded');
                toggleLink.textContent = 'Xem thêm';
            } else {
                infoCell.classList.add('expanded');
                toggleLink.textContent = 'Ẩn bớt';
            }
        }
        // Ẩn popup lịch sử nhận diện
        function hideClassificationHistory() {
            const popup = document.getElementById('classificationPopup');
            const overlay = document.getElementById('classificationOverlay');
            popup.style.display = 'none';
            overlay.style.display = 'none';
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
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('captchaForm');
            form.addEventListener('submit', function (e) {
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
                        if (data.success) {
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
        // popup chi tiết liên hệ
        // Hiển thị Popup Chi tiết Liên hệ
        function showContactPopup(id, name, phone, email, message, status, isRead) {
            const popup = document.getElementById('contactPopup');
            const overlay = document.getElementById('contactOverlay');
            document.getElementById('contactName').textContent = name;
            document.getElementById('contactPhone').textContent = phone;
            document.getElementById('contactEmail').textContent = email;
            document.getElementById('contactMessage').textContent = message;
            document.getElementById('contactStatus').textContent = status;
            // Hiển thị popup
            popup.style.display = 'block';
            overlay.style.display = 'block';
            // Nếu chưa đọc, gửi yêu cầu cập nhật trạng thái
            if (!isRead) {
                fetch(`/admin/contact/${id}/mark-read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('contactStatus').textContent = 'Đã đọc';
                            // Cập nhật giao diện bảng nếu cần
                            const row = document.querySelector(`tr[style*="${id}"]`);
                            if (row) {
                                row.style.backgroundColor = 'var(--card-bg)';
                                row.style.color = 'var(--text)';
                                row.style.borderLeft = '4px solid transparent';
                                row.querySelector('span').style.backgroundColor = 'var(--border)';
                                row.querySelector('span').style.color = 'var(--text)';
                                row.querySelector('span').textContent = 'Đã đọc';
                            }
                            updateContactNotification();
                        }
                    })
                    .catch(error => console.error('Lỗi khi cập nhật trạng thái:', error));
            }
        }
        // Ẩn Popup Chi tiết Liên hệ
        function hideContactPopup() {
            const popup = document.getElementById('contactPopup');
            const overlay = document.getElementById('contactOverlay');
            popup.style.display = 'none';
            overlay.style.display = 'none';
        }
        // Hàm cập nhật dấu chấm xanh cho tab Liên hệ
        function updateContactNotification() {
            const contactTabLink = document.querySelector('.sidebar a[data-tab="contacts"]');
            const notificationDot = contactTabLink.querySelector('.notification-dot');
            const unreadCount = @json($contacts->where('is_read', false)->count()); // Số liên hệ chưa đọc ban đầu
            // Xóa dấu chấm cũ nếu có
            if (notificationDot) {
                notificationDot.remove();
            }
            // Thêm dấu chấm nếu còn liên hệ chưa đọc
            if (unreadCount > 0) {
                const dot = document.createElement('span');
                dot.className = 'notification-dot';
                contactTabLink.appendChild(dot);
            }
        }
        // Gọi hàm khi trang tải
        document.addEventListener('DOMContentLoaded', updateContactNotification);
        // Hàm cập nhật dấu chấm xanh
        function updateRechargeNotification() {
            const rechargeTabLink = document.querySelector('.sidebar a[data-tab="recharge"]');
            const notificationDot = rechargeTabLink.querySelector('.notification-dot');
            const rechargeCount = @json($rechargeRequests->count()); // Số lượng ban đầu từ PHP
            // Xóa dấu chấm cũ nếu có
            if (notificationDot) {
                notificationDot.remove();
            }
            // Thêm dấu chấm nếu còn yêu cầu
            if (rechargeCount > 0) {
                const dot = document.createElement('span');
                dot.className = 'notification-dot';
                rechargeTabLink.appendChild(dot);
            }
        }
        // Gọi hàm khi trang tải
        document.addEventListener('DOMContentLoaded', updateRechargeNotification);
        function filterUsers() {
            const search = document.getElementById('userSearch').value.toLowerCase();
            const role = document.getElementById('roleFilter').value;
            const rows = document.querySelectorAll('#users tbody tr');
            rows.forEach(row => {
                const name = row.cells[1].textContent.toLowerCase();
                const email = row.cells[2].textContent.toLowerCase();
                const roleValue = row.cells[3].textContent.toLowerCase();
                const matchesSearch = name.includes(search) || email.includes(search);
                const matchesRole = !role || roleValue === role;
                row.style.display = matchesSearch && matchesRole ? '' : 'none';
            });
        }
        //Quản lý tin tức trang Chủ
        // Biến để lưu giá trị ban đầu của tin tức
        let initialNewsValues = {};
        // Edit news row
        function editNewsRow(newsId) {
            const row = document.getElementById(`news-row-${newsId}`);
            const editables = row.querySelectorAll('.editable');
            const editBtn = row.querySelector('.edit-btn');
            const saveBtn = row.querySelector('.save-btn');
            const cancelBtn = row.querySelector('.cancel-btn');
            initialNewsValues[newsId] = {};
            editables.forEach(cell => {
                const field = cell.dataset.field;
                const input = cell.querySelector('input, textarea');
                initialNewsValues[newsId][field] = input.value;
                cell.classList.add('editing');
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
        function cancelNewsEdit(newsId) {
            const row = document.getElementById(`news-row-${newsId}`);
            const editables = row.querySelectorAll('.editable');
            const editBtn = row.querySelector('.edit-btn');
            const saveBtn = row.querySelector('.save-btn');
            const cancelBtn = row.querySelector('.cancel-btn');
            editables.forEach(cell => {
                const display = cell.querySelector('.display');
                const input = cell.querySelector('input, textarea');
                input.style.display = 'none';
                display.style.display = 'block';
                input.value = initialNewsValues[newsId][cell.dataset.field];
                cell.classList.remove('editing');
                const contentCell = cell.querySelector('.description-cell');
                if (contentCell) {
                    contentCell.classList.remove('expanded');
                    const toggleLink = cell.querySelector('.toggle-description');
                    toggleLink.textContent = 'Xem thêm';
                }
            });
            editBtn.style.display = 'inline-flex';
            saveBtn.style.display = 'none';
            cancelBtn.style.display = 'none';
            delete initialNewsValues[newsId];
        }
        // Toggle nội dung tin tức
        function toggleNewsContent(newsId) {
            const contentCell = document.getElementById(`content-${newsId}`);
            const toggleLink = document.getElementById(`toggle-content-${newsId}`);
            if (contentCell.classList.contains('expanded')) {
                contentCell.classList.remove('expanded');
                toggleLink.textContent = 'Xem thêm';
            } else {
                contentCell.classList.add('expanded');
                toggleLink.textContent = 'Ẩn bớt';
            }
        }
        // Show/Hide Add News Popup
        function showAddNewsPopup() {
            const popup = document.getElementById('addNewsPopup');
            const overlay = document.getElementById('addNewsOverlay');
            popup.style.display = 'block';
            overlay.style.display = 'block';
        }
        function hideAddNewsPopup() {
            const popup = document.getElementById('addNewsPopup');
            const overlay = document.getElementById('addNewsOverlay');
            popup.style.display = 'none';
            overlay.style.display = 'none';
        }
        // Xử lý gửi form trong tab Quản lý tin tức
        document.querySelectorAll('#news .edit-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const newsId = this.id.replace('news-form-', '');
                const editables = document.getElementById(`news-row-${newsId}`).querySelectorAll('.editable');
                let hasChanges = false;
                const existingHiddenInputs = form.querySelectorAll('input[type="hidden"]:not([name="_token"]):not([name="_method"])');
                existingHiddenInputs.forEach(input => input.remove());
                editables.forEach(cell => {
                    const field = cell.dataset.field;
                    const input = cell.querySelector('input, textarea');
                    const currentValue = input.value;
                    const initialValue = initialNewsValues[newsId][field];
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = field;
                    hiddenInput.value = currentValue;
                    form.appendChild(hiddenInput);
                    if (currentValue !== initialValue) {
                        hasChanges = true;
                    }
                });
                if (!hasChanges) {
                    alert('Không có thay đổi để lưu!');
                    return;
                }
                form.submit();
            });
        });
        //Mới
        // Dữ liệu lịch sử giao dịch từ PHP
        const transactions = @json($transactionHistory->toArray());
        // Hiển thị lịch sử giao dịch với phân trang
        function renderTransactionHistory(page = 1) {
            const tbody = document.getElementById('transactionBody');
            const pagination = document.getElementById('transactionPagination');
            const itemsPerPage = 10;
            const totalItems = transactions.length;
            const totalPages = Math.ceil(totalItems / itemsPerPage);
            // Tính toán chỉ số bắt đầu và kết thúc
            const start = (page - 1) * itemsPerPage;
            const end = Math.min(start + itemsPerPage, totalItems);
            const paginatedTransactions = transactions.slice(start, end);
            // Xóa nội dung cũ
            tbody.innerHTML = '';
            // Thêm các hàng giao dịch
            paginatedTransactions.forEach(transaction => {
                const row = document.createElement('tr');
                row.innerHTML = `
            <td>${transaction.id}</td>
            <td>${transaction.user?.name ?? 'Người dùng không tồn tại'}</td>
            <td>${Number(transaction.amount).toLocaleString('vi-VN')} VNĐ</td>
            <td>${transaction.requested_tokens}</td>
            <td><span class="status ${transaction.status}">${transaction.status.charAt(0).toUpperCase() + transaction.status.slice(1)}</span></td>
            <td>${new Date(transaction.created_at).toLocaleString('vi-VN', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</td>
        `;
                tbody.appendChild(row);
            });
            // Tạo phân trang
            pagination.innerHTML = '';
            if (totalPages > 1) {
                // Nút "Trước"
                const prevLink = document.createElement('a');
                prevLink.textContent = 'Trước';
                prevLink.href = '#';
                prevLink.className = page === 1 ? 'disabled' : '';
                prevLink.addEventListener('click', (e) => {
                    e.preventDefault();
                    if (page > 1) renderTransactionHistory(page - 1);
                });
                pagination.appendChild(prevLink);
                // Các trang số
                for (let i = 1; i <= totalPages; i++) {
                    const pageLink = document.createElement('a');
                    pageLink.textContent = i;
                    pageLink.href = '#';
                    pageLink.className = i === page ? 'active' : '';
                    pageLink.addEventListener('click', (e) => {
                        e.preventDefault();
                        renderTransactionHistory(i);
                    });
                    pagination.appendChild(pageLink);
                }
                // Nút "Sau"
                const nextLink = document.createElement('a');
                nextLink.textContent = 'Sau';
                nextLink.href = '#';
                nextLink.className = page === totalPages ? 'disabled' : '';
                nextLink.addEventListener('click', (e) => {
                    e.preventDefault();
                    if (page < totalPages) renderTransactionHistory(page + 1);
                });
                pagination.appendChild(nextLink);
            }
        }
        // Gọi hàm khi trang tải
        document.addEventListener('DOMContentLoaded', function () {
            if (transactions.length > 0) {
                renderTransactionHistory(1); // Hiển thị trang đầu tiên
            }
            // Các hàm khác như vẽ biểu đồ vẫn giữ nguyên
            const userTrend = @json($userTrend);
            const rechargeTrend = @json($rechargeTrend);
            const revenueTrend = @json($revenueTrend);
            const ratingTrend = @json($ratingTrend);
            function createChart(canvasId, labels, values, label, color, isCurrency = false) {
                const ctx = document.getElementById(canvasId);
                if (!ctx) return;
                new Chart(ctx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: label,
                            data: values,
                            borderColor: color,
                            backgroundColor: `${color}70`,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: value => isCurrency ? value.toLocaleString('vi-VN') + ' VNĐ' : value
                                }
                            },
                            x: { ticks: { font: { size: 10 } } }
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: context => `${context.dataset.label}: ${isCurrency ? context.parsed.y.toLocaleString('vi-VN') + ' VNĐ' : context.parsed.y}`
                                }
                            }
                        }
                    }
                });
            }
            createChart('userTrendChart', userTrend.labels, userTrend.values, 'Người dùng mới', '#42a5f5');
            createChart('rechargeTrendChart', rechargeTrend.labels, rechargeTrend.values, 'Yêu cầu mới', '#ffca28');
            createChart('revenueTrendChart', revenueTrend.labels, revenueTrend.values, 'Doanh thu', '#00c853', true);
            createChart('ratingTrendChart', ratingTrend.labels, ratingTrend.values, 'Đánh giá', '#f44336');
        });
        //Thông tin hệ thống
        // Xử lý tab con trong "Thông tin hệ thống"
        function openSubTab(tabName) {
            document.querySelectorAll('#system-info .tab-content').forEach(tab => tab.style.display = 'none');
            document.querySelectorAll('#system-info .tab-button').forEach(btn => btn.classList.remove('active'));
            document.getElementById(tabName).style.display = 'block';
            document.querySelector(`#system-info .tab-button[onclick="openSubTab('${tabName}')"]`).classList.add('active');
        }
        @if($isSystemInfoEnabled)
            const fastApiRamChart = new Chart(document.getElementById('fastApiRamChart').getContext('2d'), {
                type: 'line',
                data: { labels: [], datasets: [{ label: 'RAM (MB)', data: [], borderColor: 'blue', fill: false }] },
                options: { scales: { y: { beginAtZero: true } } }
            });
            const fastApiCpuChart = new Chart(document.getElementById('fastApiCpuChart').getContext('2d'), {
                type: 'line',
                data: { labels: [], datasets: [{ label: 'CPU (%)', data: [], borderColor: 'red', fill: false }] },
                options: { scales: { y: { beginAtZero: true, max: 100 } } }
            });
            const fastApiGpuChart = new Chart(document.getElementById('fastApiGpuChart').getContext('2d'), {
                type: 'line',
                data: { labels: [], datasets: [{ label: 'GPU (%)', data: [], borderColor: 'green', fill: false }] },
                options: { scales: { y: { beginAtZero: true, max: 100 } } }
            });
            const laravelRamChart = new Chart(document.getElementById('laravelRamChart').getContext('2d'), {
                type: 'line',
                data: { labels: [], datasets: [{ label: 'RAM (MB)', data: [], borderColor: 'blue', fill: false }] },
                options: { scales: { y: { beginAtZero: true } } }
            });
            const laravelCpuChart = new Chart(document.getElementById('laravelCpuChart').getContext('2d'), {
                type: 'line',
                data: { labels: [], datasets: [{ label: 'CPU (%)', data: [], borderColor: 'red', fill: false }] },
                options: { scales: { y: { beginAtZero: true, max: 100 } } }
            });
            const laravelGpuChart = new Chart(document.getElementById('laravelGpuChart').getContext('2d'), {
                type: 'line',
                data: { labels: [], datasets: [{ label: 'GPU (%)', data: [], borderColor: 'green', fill: false }] },
                options: { scales: { y: { beginAtZero: true, max: 100 } } }
            });
            function updateSystemCharts() {
                fetch('/admin/system/fastapi-stats')
                    .then(response => response.json())
                    .then(data => {
                        if (data.message) return;
                        console.log('FastAPI Data:', data);
                        const now = new Date().toLocaleTimeString();
                        fastApiRamChart.data.labels.push(now);
                        fastApiRamChart.data.datasets[0].data.push(data.ram_used_mb || 0);
                        fastApiCpuChart.data.labels.push(now);
                        fastApiCpuChart.data.datasets[0].data.push(data.cpu_usage_percent || 0);
                        fastApiGpuChart.data.labels.push(now);
                        fastApiGpuChart.data.datasets[0].data.push(data.gpu_usage_percent || 0);
                        if (fastApiRamChart.data.labels.length > 20) {
                            fastApiRamChart.data.labels.shift();
                            fastApiRamChart.data.datasets[0].data.shift();
                            fastApiCpuChart.data.labels.shift();
                            fastApiCpuChart.data.datasets[0].data.shift();
                            fastApiGpuChart.data.labels.shift();
                            fastApiGpuChart.data.datasets[0].data.shift();
                        }
                        fastApiRamChart.update();
                        fastApiCpuChart.update();
                        fastApiGpuChart.update();
                    })
                    .catch(error => console.error('Lỗi FastAPI:', error));
                fetch('/admin/system/laravel-stats')
                    .then(response => response.json())
                    .then(data => {
                        if (data.message) return;
                        console.log('Laravel Data:', data);
                        const now = new Date().toLocaleTimeString();
                        laravelRamChart.data.labels.push(now);
                        laravelRamChart.data.datasets[0].data.push(data.ram_used || 0);
                        laravelCpuChart.data.labels.push(now);
                        laravelCpuChart.data.datasets[0].data.push(data.cpu_usage || 0);
                        laravelGpuChart.data.labels.push(now);
                        laravelGpuChart.data.datasets[0].data.push(data.gpu_usage || 0);
                        if (laravelRamChart.data.labels.length > 20) {
                            laravelRamChart.data.labels.shift();
                            laravelRamChart.data.datasets[0].data.shift();
                            laravelCpuChart.data.labels.shift();
                            laravelCpuChart.data.datasets[0].data.shift();
                            laravelGpuChart.data.labels.shift();
                            laravelGpuChart.data.datasets[0].data.shift();
                        }
                        laravelRamChart.update();
                        laravelCpuChart.update();
                        laravelGpuChart.update();
                    })
                    .catch(error => console.error('Lỗi Laravel:', error));
            }
            // Gọi lần đầu khi tải trang
            updateSystemCharts();
            // Cập nhật mỗi 5 giây
            setInterval(updateSystemCharts, 5000);
        @endif
    </script>
</body>

</html>