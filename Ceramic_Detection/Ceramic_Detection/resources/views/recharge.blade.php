<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nạp Tiền - Ceramic Recognition</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        :root {
            --primary-color: #1e88e5;
            --secondary-color: #42a5f5;
            --accent-color: #eceff1;
            --light-color: #f5f7fa;
            --dark-color: #263238;
            --success-color: #00c853;
            --gradient: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(to bottom right, #4facfe, #ffffff);
            color: var(--dark-color);
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            width: 70px;
            background: var(--primary-color);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            padding: 20px 0;
            transition: width 0.3s ease;
            box-shadow: 2px 0 15px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .sidebar:hover {
            width: 240px;
        }

        .sidebar .logo {
            text-align: center;
            padding: 15px 0;
            margin-bottom: 20px;
        }

        .sidebar .logo i {
            font-size: 2rem;
            color: white;
            transition: transform 0.3s ease;
        }

        .sidebar:hover .logo i {
            transform: rotate(360deg);
        }

        .sidebar ul {
            list-style: none;
        }

        .sidebar li {
            margin: 10px 0;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: white;
            text-decoration: none;
            font-size: 1rem;
            transition: background 0.3s, padding-left 0.3s;
        }

        .sidebar a:hover, .sidebar a.active {
            background: var(--secondary-color);
            padding-left: 30px;
        }

        .sidebar a i {
            font-size: 1.2rem;
            min-width: 30px;
        }

        .sidebar a span {
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .sidebar:hover a span {
            opacity: 1;
        }

        /* Main content */
        .container {
            margin: auto;
            flex: 1;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            animation: fadeIn 0.5s ease-in;
        }

        .header {
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            text-align: center;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .header h1 {
            font-size: 2.2rem;
            font-weight: 600;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .user-info {
            font-size: 1.2rem;
            color: var(--dark-color);
            margin-top: 8px;
        }

        .user-info a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .user-info a:hover {
            color: var(--secondary-color);
        }

        .content-wrapper {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .section {
            background: var(--light-color);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .section:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .section h3 {
            font-size: 1.5rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        /* Recharge Section - Card Selection Style */
        .recharge-options {
            display: flex;
            flex-wrap: nowrap; /* Đảm bảo các thẻ nằm trên một hàng ngang */
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 30px;
        }

        .recharge-options label {
            flex: 1;
            position: relative;
            cursor: pointer;
        }

        .recharge-options input[type="radio"] {
            display: none; /* Ẩn input radio */
        }

        .recharge-options .card {
            padding: 20px;
            background: white;
            border-radius: 12px;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            width: 100%;
        }

        .recharge-options label:hover .card {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            border: 2px solid var(--primary-color);
        }

        .recharge-options input[type="radio"]:checked + .card {
            background: var(--gradient);
            color: white;
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            border: none;
        }

        .recharge-options .card .amount {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .recharge-options .card .tokens {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 5px;
        }

        .recharge-options .card .description {
            font-size: 0.8rem;
            color: #888;
            font-style: italic;
        }

        .recharge-options input[type="radio"]:checked + .card .tokens,
        .recharge-options input[type="radio"]:checked + .card .description {
            color: white;
        }

        .recharge-options .card .icon {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 1.5rem;
            color: var(--primary-color);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .recharge-options input[type="radio"]:checked + .card .icon {
            opacity: 1;
            color: white;
        }

        .qr-code {
            text-align: center;
            margin-bottom: 30px;
        }

        .qr-code img {
            width: 150px;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .qr-code img:hover {
            transform: scale(1.1);
        }

        .qr-code p {
            margin-top: 10px;
            font-size: 0.9rem;
            color: var(--dark-color);
        }

        .proof-upload {
            margin-bottom: 30px;
        }

        .proof-upload input[type="file"] {
            padding: 10px;
            border: 2px dashed var(--accent-color);
            border-radius: 10px;
            width: 100%;
            cursor: pointer;
            transition: border-color 0.3s;
        }

        .proof-upload input[type="file"]:hover {
            border-color: var(--primary-color);
        }

        #proofPreview {
            max-width: 100%;
            max-height: 200px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.5s ease-in;
            display: none;
        }

        button[type="submit"] {
            display: block;
            width: 100%;
            padding: 15px;
            background: var(--gradient);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
        }

        button[type="submit"]:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }

        /* History Section */
        .recharge-history ul {
            list-style: none;
        }

        .recharge-history li {
            padding: 15px;
            background: white;
            border-radius: 10px;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s;
        }

        .recharge-history li:hover {
            transform: translateY(-3px);
        }

        .recharge-history button.export-btn {
            padding: 8px 15px;
            background: var(--success-color);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .recharge-history button.export-btn:hover {
            background: #00a843;
            transform: translateY(-2px);
        }

        /* Notification Section */
        .notification-messages {
            max-height: 300px;
            overflow-y: auto;
            margin-bottom: 20px;
        }

        .message {
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
        }

        .message.admin {
            background: #f0f0f0;
            text-align: left;
        }

        .message span {
            font-size: 0.8rem;
            color: #777;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @media (max-width: 768px) {
            .sidebar { width: 60px; }
            .sidebar:hover { width: 200px; }
            .container { margin-left: 80px; padding: 10px; }
            .recharge-options { flex-direction: column; }
            .recharge-options label { margin-bottom: 10px; }
            .header h1 { font-size: 1.8rem; }
            .user-info { font-size: 0.9rem; }
        }

        @media (max-width: 480px) {
            .container { margin-left: 70px; padding: 8px; }
            .qr-code img { width: 120px; }
        }
    </style>
</head>
<body>
    @if (!Auth::check())
        <script>
            window.location.href = "{{ route('login') }}";
        </script>
    @endif

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <i class="fas fa-wallet"></i>
        </div>
        <ul>
            <li><a href="#" class="active" data-section="recharge-section"><i class="fas fa-money-bill-wave"></i><span>Nạp Tiền</span></a></li>
            <li><a href="#" data-section="recharge-history"><i class="fas fa-history"></i><span>Lịch Sử Nạp Tiền</span></a></li>
            <li><a href="#" data-section="notifications"><i class="fas fa-bell"></i><span>Thông Báo</span></a></li>
            <li><a href="/dashboard"><i class="fas fa-tachometer-alt"></i><span>Quay Lại Dashboard</span></a></li>
        </ul>
    </div>

    <!-- Main content -->
    <div class="container">
        <div class="header">
            <h1>Nạp Tiền</h1>
            <div class="user-info">
                Xin chào, {{ Auth::user()->name }}! Bạn hiện có <span id="tokenCount">{{ Auth::user()->tokens }}</span> lượt dự đoán.
            </div>
        </div>

        <div class="content-wrapper">
            <!-- Recharge Section -->
            <div class="section recharge-section" id="recharge-section">
                <h3>Chọn Số Tiền Nạp</h3>
                @if (session('success'))
                    <p style="color: var(--success-color); text-align: center; margin-bottom: 20px;">{{ session('success') }}</p>
                @endif
                <form id="rechargeForm" method="POST" action="{{ route('recharge.submit') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="recharge-options">
                        <label for="amount1">
                            <input type="radio" name="amount" value="50000" id="amount1" required>
                            <div class="card">
                                <div class="amount">50,000 VNĐ</div>
                                <div class="tokens">50 tokens</div>
                                <div class="description">Phù hợp cho người mới</div>
                                <i class="fas fa-check-circle icon"></i>
                            </div>
                        </label>

                        <label for="amount2">
                            <input type="radio" name="amount" value="100000" id="amount2">
                            <div class="card">
                                <div class="amount">100,000 VNĐ</div>
                                <div class="tokens">110 tokens</div>
                                <div class="description">Tiết kiệm nhất</div>
                                <i class="fas fa-check-circle icon"></i>
                            </div>
                        </label>

                        <label for="amount3">
                            <input type="radio" name="amount" value="200000" id="amount3">
                            <div class="card">
                                <div class="amount">200,000 VNĐ</div>
                                <div class="tokens">240 tokens</div>
                                <div class="description">Dành cho người dùng thường xuyên</div>
                                <i class="fas fa-check-circle icon"></i>
                            </div>
                        </label>
                    </div>

                    <div class="qr-code">
                        <h4>Quét Mã QR Để Chuyển Khoản</h4>
                        <img src="\images\1743491347043.png" alt="QR Code Agribank">
                        <p>Ngân hàng: Agribank<br>STK: 7206205146190<br>Vui lòng ghi rõ số tiền khi chuyển khoản!</p>
                    </div>

                    <div class="proof-upload">
                        <h4>Tải Lên Ảnh Chứng Minh Chuyển Khoản</h4>
                        <input type="file" name="proof_image" id="proofImage" accept="image/*" required>
                        <img id="proofPreview" src="" alt="Proof preview">
                    </div>

                    <button type="submit">Gửi Yêu Cầu Thêm Tokens</button>
                </form>
            </div>

            <!-- Recharge History Section -->
            <div class="section recharge-history" id="recharge-history">
                <h3>Lịch Sử Nạp Tiền</h3>
                @if ($rechargeHistory->isEmpty())
                    <p>Bạn chưa có lịch sử nạp tiền.</p>
                @else
                    <ul>
                        @foreach ($rechargeHistory as $record)
                            <li>
                                <span>
                                    Nạp {{ number_format($record->amount) }} VNĐ → Nhận {{ $record->tokens_added }} tokens 
                                    ({{ $record->approved_at }})
                                </span>
                                <button class="export-btn" onclick="exportReceipt({{ $record->id }})">Xuất Hóa Đơn</button>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <!-- Notifications Section -->
            <div class="section notifications" id="notifications">
                <h3>Thông Báo Từ Admin</h3>
                <div class="notification-messages">
                    @if ($messages->isEmpty())
                        <p>Chưa có thông báo nào từ admin.</p>
                    @else
                        @foreach ($messages as $message)
                            @if ($message->admin_id)
                                <div class="message admin">
                                    <p>{{ $message->message }}</p>
                                    <span>{{ $message->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/dashboard.js') }}"></script>
    <script>
        // Sidebar navigation
        document.querySelectorAll('.sidebar a').forEach(link => {
            link.addEventListener('click', function(e) {
                if (!this.href.includes('/dashboard')) {
                    e.preventDefault();
                    document.querySelectorAll('.sidebar a').forEach(a => a.classList.remove('active'));
                    this.classList.add('active');
                    document.querySelectorAll('.section').forEach(section => section.style.display = 'none');
                    const section = document.getElementById(this.dataset.section);
                    if (section) section.style.display = 'block';
                }
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.section').forEach(section => section.style.display = 'none');
            document.getElementById('recharge-section').style.display = 'block';
        });

        // Form submission confirmation
        document.getElementById('rechargeForm').addEventListener('submit', function(e) {
            if (!confirm('Bạn có chắc chắn muốn gửi yêu cầu nạp tiền này?')) {
                e.preventDefault();
            }
        });

        // Preview proof image
        document.getElementById('proofImage').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('proofPreview');
            if (file) {
                preview.src = URL.createObjectURL(file);
                preview.style.display = 'block';
            }
        });

        // Export receipt
        function exportReceipt(recordId) {
            fetch(`/recharge/export/${recordId}`, { method: 'GET' })
                .then(response => response.blob())
                .then(blob => {
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `HoaDon_NapTien_${recordId}.pdf`;
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                })
                .catch(error => alert('Lỗi khi xuất hóa đơn: ' + error));
        }
    </script>
</body>
</html>