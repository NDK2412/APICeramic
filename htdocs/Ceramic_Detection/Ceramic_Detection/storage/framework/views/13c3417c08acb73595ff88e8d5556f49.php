<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Nạp Tiền - Ceramic Recognition</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="<?php echo e(asset('css/dashboard.css')); ?>">
    <!-- Thêm script Google reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        :root {
            --primary-color: rgb(38, 70, 82);
            --secondary-color: rgb(118, 218, 236);
            --accent-color: #eceff1;
            --light-color: #f5f7fa;
            --dark-color: #263238;
            --success-color: #00c853;
            --gradient: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            --pending-color: #ffca28;
            /* Màu vàng cho yêu cầu đang chờ */
            --approved-color: #00c853;
            /* Màu xanh lá cho yêu cầu đã duyệt */
            --amount-color: #42a5f5;
            /* Màu xanh dương cho tổng số tiền */
            --tokens-color: #f44336;
            /* Màu đỏ cho tổng token */
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(to bottom right, rgb(12, 40, 64), rgb(182, 192, 218));
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
            width: 300px;

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

        .sidebar a:hover,
        .sidebar a.active {
            background: var(--secondary-color);
            padding-left: 30px;
            padding: auto;
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
            flex-wrap: nowrap;
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
            display: none;
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

        .recharge-options input[type="radio"]:checked+.card {
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

        .recharge-options input[type="radio"]:checked+.card .tokens,
        .recharge-options input[type="radio"]:checked+.card .description {
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

        .recharge-options input[type="radio"]:checked+.card .icon {
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

        /* Popup Styles */
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

        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            width: 400px;
            max-width: 90%;
        }

        .popup h3 {
            font-size: 1.4rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .popup input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid var(--accent-color);
            border-radius: 4px;
        }

        .popup .g-recaptcha {
            margin-bottom: 10px;
        }

        .popup button {
            background: var(--gradient);
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 1rem;
            width: 100%;
        }

        .popup button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 60px;
            }

            .sidebar:hover {
                width: 200px;
            }

            .container {
                margin-left: 80px;
                padding: 10px;
            }

            .recharge-options {
                flex-direction: column;
            }

            .recharge-options label {
                margin-bottom: 10px;
            }

            .header h1 {
                font-size: 1.8rem;
            }

            .user-info {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            .container {
                margin-left: 70px;
                padding: 8px;
            }

            .qr-code img {
                width: 120px;
            }
        }

        .stats-section {
            background: var(--light-color);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            margin-bottom: 15px;
        }

        .stats-section h3 {
            font-size: 1.5rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .stat-card {
            background: white;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card h4 {
            font-size: 1.1rem;
            color: var(--dark-color);
            margin-bottom: 5px;
        }

        .stat-card p {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--dark-color);
        }

        .stat-card.pending {
            background: var(--pending-color);
            color: white;
        }

        .stat-card.approved {
            background: var(--approved-color);
            color: white;
        }

        .stat-card.amount {
            background: var(--amount-color);
            color: white;
        }

        .stat-card.tokens {
            background: var(--tokens-color);
            color: white;
        }

        .stat-card.pending h4,
        .stat-card.approved h4,
        .stat-card.amount h4,
        .stat-card.tokens h4 {
            color: white;
        }
    </style>
</head>

<body>
    <?php if(!Auth::check()): ?>
        <script>
            window.location.href = "<?php echo e(route('login')); ?>";
        </script>
    <?php endif; ?>

    <div class="sidebar">
        <div class="logo">
            <i class="fas fa-wallet"></i>
        </div>
        <ul>
            <li><a href="#" class="active" data-section="recharge-section"><i
                        class="fas fa-money-bill-wave"></i><span>Nạp Tiền</span></a></li>
            <li><a href="#" data-section="recharge-history"><i class="fas fa-history"></i><span>Lịch Sử</span></a></li>
            <li><a href="#" data-section="notifications"><i class="fas fa-bell"></i><span>Thông Báo</span></a></li>
            <li><a href="/dashboard"><i class="fas fa-tachometer-alt"></i><span>Quay Lại Dashboard</span></a></li>
        </ul>
    </div>

    <!-- Main content -->
    <div class="container">
        <div class="header">
            <h1>Nạp Tiền</h1>
            <div class="user-info"><h2>
                Xin chào, <?php echo e(Auth::user()->name); ?>! Bạn hiện có <span id="tokenCount"><?php echo e(Auth::user()->tokens); ?></span>
                lượt dự đoán.</h2>
            </div>
        </div>

        <div class="content-wrapper">
            <!-- Recharge Stats Section - Không dùng class .section -->
            <div class="stats-section">
                <h3>Thống Kê Nạp Tiền</h3>
                <div class="stats-grid">
                    <div class="stat-card pending">
                        <h4>Yêu Cầu Đang Chờ</h4>
                        <p><?php echo e($pendingRequestsCount); ?></p>
                    </div>
                    <div class="stat-card approved">
                        <h4>Yêu Cầu Đã Duyệt</h4>
                        <p><?php echo e($approvedRequestsCount); ?></p>
                    </div>
                    <div class="stat-card amount">
                        <h4>Tổng Số Tiền Đã Nạp</h4>
                        <p><?php echo e(number_format($totalAmount)); ?> VNĐ</p>
                    </div>
                    <div class="stat-card tokens">
                        <h4>Tổng Token Đã Nạp</h4>
                        <p><?php echo e($totalTokens); ?></p>
                    </div>
                </div>
            </div>

            <!-- Recharge Section -->
            <div class="section recharge-section" id="recharge-section">
                <h3>Chọn Số Tiền Nạp</h3>
                <?php if(session('success')): ?>
                    <p style="color: var(--success-color); text-align: center; margin-bottom: 20px;">
                        <?php echo e(session('success')); ?>

                    </p>
                <?php endif; ?>
                <form id="rechargeForm" method="POST" action="<?php echo e(route('recharge.submit')); ?>"
                    enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="recharge-options">
                        <?php $__empty_1 = true; $__currentLoopData = $packages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $package): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <label for="amount<?php echo e($index); ?>">
                                <input type="radio" name="package_id" value="<?php echo e($package->id); ?>" id="amount<?php echo e($index); ?>" <?php echo e($index === 0 ? 'required' : ''); ?>>
                                <div class="card">
                                    <div class="amount"><?php echo e(number_format($package->amount)); ?> VNĐ</div>
                                    <div class="tokens"><?php echo e($package->tokens); ?> tokens</div>
                                    <div class="description"><?php echo e($package->description ?? 'Không có mô tả'); ?></div>
                                    <i class="fas fa-check-circle icon"></i>
                                </div>
                            </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <p>Không có gói nạp tiền nào khả dụng.</p>
                        <?php endif; ?>
                    </div>

                    <div class="qr-code">
                        <h4>Quét Mã QR Để Chuyển Khoản</h4>
                        <img src="/images/1743491347043.png" alt="QR Code Agribank">
                        <p>Ngân hàng: Agribank<br>STK: 7206205146190<br>Vui lòng chụp rõ số tiền khi chuyển khoản!</p>
                    </div>

                    <div class="proof-upload">
                        <h4>Tải Lên Ảnh Chứng Minh Chuyển Khoản</h4>
                        <input type="file" name="proof_image" id="proofImage" accept="image/*" required>
                        <img id="proofPreview" src="" alt="Proof preview">
                    </div>

                    <button type="submit">Xác Nhận Mua Thêm Lượt</button>
                </form>
            </div>
        </div>

        <!-- Recharge History Section -->
        <div class="section recharge-history" id="recharge-history">
            <h3>Lịch Sử Nạp Tiền</h3>
            <?php if($rechargeHistory->isEmpty()): ?>
                <p>Bạn chưa có lịch sử nạp tiền.</p>
            <?php else: ?>
                <ul>
                    <?php $__currentLoopData = $rechargeHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <span>
                                Nạp <?php echo e(number_format($record->amount)); ?> VNĐ → Nhận <?php echo e($record->tokens_added); ?> tokens
                                (<?php echo e($record->approved_at); ?>)
                            </span>
                            <button class="export-btn" onclick="exportReceipt(<?php echo e($record->id); ?>)">Xuất Hóa Đơn</button>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            <?php endif; ?>
        </div>

        <!-- Notifications Section -->
        <div class="section notifications" id="notifications">
            <h3>Thông Báo Từ Admin</h3>
            <div class="notification-messages">
                <?php if($messages->isEmpty()): ?>
                    <p>Chưa có thông báo nào từ admin.</p>
                <?php else: ?>
                    <?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($message->admin_id): ?>
                            <div class="message admin">
                                <p><?php echo e($message->message); ?></p>
                                <span><?php echo e($message->created_at->format('d/m/Y H:i')); ?></span>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Popup Xác Nhận Mật Khẩu và CAPTCHA -->
        <div class="popup-overlay" id="confirmPopupOverlay" onclick="hideConfirmPopup()"></div>
        <div class="popup" id="confirmPopup">
            <h3>Xác Nhận Giao Dịch</h3>
            <form id="confirmForm">
                <p><strong>Nhập lại mật khẩu:</strong></p>
                <input type="password" id="confirmPassword" name="password" required
                    placeholder="Nhập mật khẩu của bạn">
                <p><strong>Xác nhận CAPTCHA:</strong></p>
                <div class="g-recaptcha" data-sitekey="<?php echo e(env('RECAPTCHA_SITE_KEY')); ?>"></div>
                <button type="button" onclick="submitRechargeForm()">Xác Nhận</button>
            </form>
        </div>
    </div>
    </div>

    <script src="<?php echo e(asset('js/dashboard.js')); ?>"></script>
    <script>
        // Sidebar navigation
        document.querySelectorAll('.sidebar a').forEach(link => {
            link.addEventListener('click', function (e) {
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
            document.querySelectorAll('.section').forEach(section => {
                if (section.id !== 'stats-section') {
                    section.style.display = 'none';
                }
            });
            document.getElementById('recharge-section').style.display = 'block';
        });

        // Hiển thị popup xác nhận khi nhấn nút "Xác Nhận Mua Thêm Lượt"
        document.getElementById('rechargeForm').addEventListener('submit', function (e) {
            e.preventDefault();
            showConfirmPopup();
        });

        function showConfirmPopup() {
            const popup = document.getElementById('confirmPopup');
            const overlay = document.getElementById('confirmPopupOverlay');
            popup.style.display = 'block';
            overlay.style.display = 'block';
            grecaptcha.reset();
        }

        function hideConfirmPopup() {
            const popup = document.getElementById('confirmPopup');
            const overlay = document.getElementById('confirmPopupOverlay');
            popup.style.display = 'none';
            overlay.style.display = 'none';
            document.getElementById('confirmPassword').value = '';
        }

        function submitRechargeForm() {
            const password = document.getElementById('confirmPassword').value;
            const recaptchaResponse = grecaptcha.getResponse();

            if (!password) {
                alert('Vui lòng nhập mật khẩu!');
                return;
            }

            if (!recaptchaResponse) {
                alert('Vui lòng xác nhận CAPTCHA!');
                return;
            }

            fetch('/recharge/verify', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    password: password,
                    'g-recaptcha-response': recaptchaResponse
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('rechargeForm').submit();
                    } else {
                        alert(data.message || 'Mật khẩu hoặc CAPTCHA không hợp lệ!');
                        grecaptcha.reset();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra. Vui lòng thử lại!');
                    grecaptcha.reset();
                });
        }

        document.getElementById('proofImage').addEventListener('change', function (e) {
            const file = e.target.files[0];
            const preview = document.getElementById('proofPreview');
            if (file) {
                preview.src = URL.createObjectURL(file);
                preview.style.display = 'block';
            }
        });

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

</html><?php /**PATH D:\Xampp\htdocs\Ceramic_Detection\Ceramic_Detection\resources\views/recharge.blade.php ENDPATH**/ ?>