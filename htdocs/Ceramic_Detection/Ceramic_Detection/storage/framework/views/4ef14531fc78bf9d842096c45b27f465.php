<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Ceramic Classification</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <!-- Thêm Font Awesome để sử dụng icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/public.css">
    <style>
        :root {
            --primary-color: #b3cde0;
            /* Xanh lam nhạt */
            --secondary-color: #6497b1;
            /* Xanh lam trung */
            --accent-color: #e6f0fa;
            /* Xanh lam rất nhạt */
            --light-color: #f5faff;
            /* Nền nhạt */
            --dark-color: #03396c;
            /* Xanh lam đậm */
            --text-light: #ffffff;
            /* Trắng cho chữ */
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: var(--light-color);
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
            flex: 1;
        }

        header {
            background-color: var(--primary-color);
            padding: 1rem 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
            width: 100%;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .logo {
            display: flex;
            align-items: center;
            color: var(--dark-color);
            font-size: clamp(1.2rem, 2.5vw, 1.8rem);
            font-weight: 600;
            text-decoration: none;
            flex-shrink: 0;
        }

        .logo img {
            height: clamp(50px, 5vw, 50px);
            margin-right: 10px;
        }

        .nav-container {
            display: flex;
            align-items: center;
        }

        .nav-menu {
            list-style: none;
            display: flex;
            gap: clamp(1rem, 2vw, 1.5rem);
        }

        .nav-menu li a {
            color: var(--dark-color);
            text-decoration: none;
            font-weight: 500;
            font-size: clamp(0.9rem, 1.5vw, 1rem);
            transition: color 0.3s ease;
        }

        .nav-menu li a:hover {
            color: var(--secondary-color);
        }

        .login-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .login-section button {
            padding: clamp(0.5rem, 1vw, 0.6rem) clamp(1rem, 2vw, 1.2rem);
            border: none;
            border-radius: 20px;
            font-weight: 500;
            font-size: clamp(0.8rem, 1.2vw, 1rem);
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        #loginButton {
            background-color: var(--secondary-color);
            color: var(--text-light);
            margin-bottom: 10px;
            animation: bounce 1s infinite;
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            }

            50% {
                transform: translateY(-12px);
                box-shadow: 0 15px 20px rgba(0, 0, 0, 0.2);
            }
        }

        #logoutButton {
            background-color: var(--dark-color);
            color: var(--text-light);
        }

        .login-section button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.15);
        }

        /* Hamburger Menu */
        .hamburger {
            display: none;
            font-size: 2rem;
            background: none;
            border: none;
            color: var(--dark-color);
            cursor: pointer;
            padding: 0.5rem;
        }

        /* Banner Styles */
        .banner {
            position: relative;
            width: 100%;
            height: 400px;
            overflow: hidden;
            border-radius: 10px;
            margin-bottom: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .banner-slides {
            display: flex;
            width: 100%;
            height: 100%;
            transition: transform 0.5s ease-in-out;
        }

        .banner-slide {
            min-width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* News Section */
        .news-section {
            padding: 2rem 0;
        }

        .news-section h1 {
            font-size: 2.5rem;
            color: var(--dark-color);
            text-align: center;
            margin-bottom: 2rem;
        }

        .news-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .news-item {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .news-item:hover {
            transform: translateY(-5px);
        }

        .news-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .news-content {
            padding: 1.5rem;
        }

        .news-content h2 {
            font-size: 1.5rem;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }

        .news-content p {
            font-size: 1rem;
            color: #666;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .news-content a {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .news-content a:hover {
            color: var(--dark-color);
        }

        /* About Section */
        .about-section {
            background-color: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            text-align: center;
            animation: fadeIn 1s ease-out;
        }

        .feature-point {
            margin: 2rem 0;
            text-align: left;
            padding: 1rem;
            background-color: var(--accent-color);
            border-radius: 8px;
        }

        .feature-point h3 {
            color: var(--dark-color);
            margin-bottom: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .feature-point h3 i {
            color: var(--secondary-color);
        }

        .feature-point p {
            color: #555;
            line-height: 1.7;
        }

        .benefits-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin: 1.5rem 0;
        }

        .benefit-card {
            background-color: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
        }

        .benefit-card:hover {
            transform: translateY(-5px);
        }

        .benefit-card i {
            font-size: 2rem;
            color: var(--secondary-color);
            margin-bottom: 1rem;
        }

        .benefit-card h4 {
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }

        .applications-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1rem;
            margin: 1.5rem 0;
        }

        .application-tag {
            background-color: var(--primary-color);
            color: var(--dark-color);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        footer {
            text-align: center;
            padding: 1.5rem;
            background-color: var(--primary-color);
            color: var(--dark-color);
            margin-top: auto;
            width: 100%;
            font-size: clamp(0.8rem, 1.5vw, 1rem);
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 2000;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 2rem;
            border-radius: 10px;
            text-align: center;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-content p {
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
            color: var(--dark-color);
        }

        .modal-content button {
            padding: 0.6rem 1.5rem;
            background-color: var(--primary-color);
            color: var(--dark-color);
            border: none;
            border-radius: 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .modal-content button:hover {
            background-color: var(--secondary-color);
            color: var(--text-light);
        }

        /* Contact Sidebar Styles */
        .contact-sidebar {
            position: fixed;
            top: 0;
            right: -450px;
            width: 450px;
            height: 100%;
            background-color: var(--primary-color);
            color: var(--dark-color);
            padding: 2rem;
            z-index: 1500;
            transition: right 0.3s ease-in-out;
            box-shadow: -2px 0 10px rgba(0, 0, 0, 0.2);
        }

        .contact-sidebar.active {
            overflow: scroll;
            right: 0;
        }

        .contact-sidebar h3 {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .contact-sidebar ul {
            list-style: none;
        }

        .contact-sidebar li {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            font-size: 1rem;
        }

        .contact-sidebar i {
            margin-right: 10px;
            font-size: 1.2rem;
            width: 20px;
            text-align: center;
        }

        .contact-sidebar a {
            color: var(--dark-color);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .contact-sidebar a:hover {
            color: var(--secondary-color);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hamburger {
                display: block;
            }

            .nav-container {
                display: none;
                flex-direction: column;
                width: 100%;
                position: absolute;
                top: 100%;
                left: 0;
                background-color: var(--primary-color);
                padding: 1rem;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            }

            .nav-container.active {
                display: flex;
            }

            .nav-menu {
                flex-direction: column;
                width: 100%;
                gap: 1rem;
                text-align: center;
            }

            .nav-menu li {
                width: 100%;
            }

            .login-section {
                width: 100%;
                justify-content: center;
                flex-direction: column;
                gap: 0.8rem;
            }

            .login-section button {
                width: 100%;
            }

            .header-content {
                justify-content: space-between;
            }

            .contact-sidebar {
                width: 250px;
            }

            .about-section,
            .news-section {
                padding: 1.5rem;
            }

            .about-section h2,
            .news-section h1 {
                font-size: 1.5rem;
            }

            .about-section p,
            .news-content p {
                font-size: 1rem;
            }

            .banner {
                height: 250px;
            }

            .news-item img {
                height: 150px;
            }

            .news-content h2 {
                font-size: 1.3rem;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 1rem;
            }

            .logo {
                font-size: 1.2rem;
            }

            .logo img {
                width: 100%;
                height: auto;
            }

            .modal-content {
                padding: 1.5rem;
            }

            .modal-content p {
                font-size: 1rem;
            }

            .contact-sidebar {
                width: 200px;
                padding: 1.5rem;
            }

            .contact-sidebar h3 {
                font-size: 1.2rem;
            }

            .contact-sidebar li {
                font-size: 0.9rem;
            }

            .banner {
                height: 200px;
            }

            .news-item img {
                height: 120px;
            }

            .news-content h2 {
                font-size: 1.2rem;
            }

            .news-content p {
                font-size: 0.85rem;
            }
        }

        /* Phần Form Liên hệ */
        .contact-form {
            margin-top: 1.5rem;
        }

        .contact-form h4 {
            font-size: 1.2rem;
            margin-bottom: 1rem;
            color: var(--secondary-color);
        }

        .contact-form input,
        .contact-form textarea {
            width: 100%;
            padding: 0.6rem;
            margin-bottom: 0.8rem;
            border: none;
            border-radius: 5px;
            background-color: var(--text-light);
            color: var(--text-dark);
            font-size: 0.9rem;
        }

        .contact-form textarea {
            height: 100px;
            resize: none;
        }

        .contact-form button {
            background-color: rgb(5, 53, 66);
            color: var(--text-light);
            padding: 0.6rem 1.2rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
            width: 100%;
        }

        .contact-form button:hover {
            background-color: var(--secondary-color);
            color: var(--text-dark);
        }
    </style>
</head>

<body>
    <div class="container">
        <header>
            <div class="header-content">
                <a href="#" class="logo">
                    <img src="<?php echo e(asset('storage/ceramics/logo2.webp')); ?>" alt="Logo">
                    Ceramic Classification
                </a>
                <button class="hamburger" aria-label="Toggle menu">☰</button>
                <div class="nav-container">
                    <ul class="nav-menu">
                        <li><a href="/">Trang chủ</a></li>
                        <li><a href="gallery">Thư viện đồ gốm</a></li>
                        <li><a href="#" id="classificationLink">Nhận diện</a></li>
                        <li><a href="#market">Mua bán</a></li>
                        <li><a href="#" id="contactLink">Liên hệ</a></li>
                    </ul>
                </div>
            </div>
        </header>
        <div class="login-section">
            <button id="loginButton" onclick="redirectToLogin()">Try It Out <i
                    class="fa-solid fa-arrow-up-from-bracket"></i></button>
            <button id="logoutButton" onclick="logout()" style="display:none;">Đăng xuất</button>
        </div>

        <!-- Banner Section -->
        <div class="banner">
            <div class="banner-slides" id="bannerSlides">
                <img src="https://www.metizsoft.com/wp-content/uploads/2025/02/Laravel-12-Latest-Features-and-Updates.webp"
                    alt="Banner 1" class="banner-slide">
                <img src="https://s3-ap-southeast-1.amazonaws.com/vmixvn/wp-media-folder-vmix-viet-nam/wp-content/uploads/2024/08/ai_trong_thi_t_k_logo.jpg"
                    alt="Banner 2" class="banner-slide">
                <img src="https://thietkelogo.edu.vn/uploads/.thumbs/images/tuyen-dung/1-php-developer-1417084819.jpg"
                    alt="Banner 3" class="banner-slide">
            </div>
        </div>

        <!-- News Section -->
        <section class="news-section">
            <h1>Tin tức về gốm sứ</h1>
            <div class="news-list">
                <?php if($news->isEmpty()): ?>
                    <p>Chưa có tin tức nào.</p>
                <?php else: ?>
                    <?php $__currentLoopData = $news; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <article class="news-item">
                            <img src="<?php echo e(url('/storage/' . $article->image)); ?>" alt="<?php echo e($article->title); ?>">
                            <div class="news-content">
                                <h2><?php echo e($article->title); ?></h2>
                                <p><?php echo e($article->excerpt ?? Str::limit($article->content, 100)); ?></p>
                                <a href="<?php echo e(route('news.detail', $article->id)); ?>">Đọc thêm</a>
                            </div>
                        </article>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </div>
        </section>
        <!-- <section class="news-section">
            <h1>Tin tức về gốm sứ</h1>
            <div class="news-list">
                <article class="news-item">
                    <img src="https://file3.qdnd.vn/data/images/0/2024/10/08/upload_2049/gom1.jpg" alt="News 1">
                    <div class="news-content">
                        <h2>Triển lãm gốm sứ 2023</h2>
                        <p>Triển lãm gốm sứ quốc tế diễn ra tại Hà Nội, thu hút hàng trăm nghệ nhân và nhà sưu tập...</p>
                        <a href="#news1">Đọc thêm</a>
                    </div>
                </article>
                <article class="news-item">
                    <img src="https://www.gomnghethuat.com/wp-content/uploads/5-Buoc-Lam-Do-Gom-Quy-Trinh-Chi-Tiet-Lam-Do-Gom-Ban-Can-Biet.jpg" alt="News 2">
                    <div class="news-content">
                        <h2>Kỹ thuật làm gốm cổ truyền</h2>
                        <p>Tìm hiểu về các phương pháp làm gốm truyền thống đang được bảo tồn tại Việt Nam...</p>
                        <a href="#news2">Đọc thêm</a>
                    </div>
                </article>
                <article class="news-item">
                    <img src="https://chus.vn/images/Blog/G%E1%BB%91m%20trong%20%C4%91%E1%BB%9Di%20s%E1%BB%91ng/go%CC%82%CC%81m%20xu%CC%9Ba%207.jpg?1713503045290" alt="News 3">
                    <div class="news-content">
                        <h2>Xu hướng gốm sứ hiện đại</h2>
                        <p>Gốm sứ không chỉ là nghệ thuật mà còn là xu hướng trang trí nội thất mới...</p>
                        <a href="#news3">Đọc thêm</a>
                    </div>
                </article>
            </div>
        </section> -->

        <!-- About Section - Updated Version -->
        <section class="about-section">
            <h2>Giới thiệu tổng quan về Ceramic AI</h2>

            <div class="feature-point">
                <h3><i class="fas fa-question-circle"></i> Ceramic AI là gì?</h3>
                <p>Ceramic AI là hệ thống trí tuệ nhân tạo tiên tiến chuyên về nhận dạng và phân loại đồ gốm sứ.
                    Công nghệ của chúng tôi kết hợp học máy và cơ sở dữ liệu phong phú để xác định chính xác niên đại,
                    xuất xứ và loại hình gốm sứ chỉ từ hình ảnh đầu vào.</p>
            </div>

            <div class="feature-point">
                <h3><i class="fas fa-star"></i> Lợi ích nổi bật</h3>
                <div class="benefits-grid">
                    <div class="benefit-card">
                        <i class="fas fa-bolt"></i>
                        <h4>Nhanh chóng</h4>
                        <p>Kết quả phân tích chỉ trong vài giây, tiết kiệm thời gian nghiên cứu</p>
                    </div>
                    <div class="benefit-card">
                        <i class="fas fa-bullseye"></i>
                        <h4>Chính xác</h4>
                        <p>Độ chính xác lên đến 75% nhờ thuật toán AI được đào tạo chuyên sâu</p>
                    </div>
                    <div class="benefit-card">
                        <i class="fas fa-gem"></i>
                        <h4>Miễn phí/Có gói</h4>
                        <p>Sử dụng cơ bản miễn phí hoặc nâng cấp gói cao cấp với nhiều tính năng</p>
                    </div>
                </div>
            </div>

            <div class="feature-point">
                <h3><i class="fas fa-cubes"></i> Ứng dụng đa dạng</h3>
                <p>Ceramic AI phục vụ nhiều đối tượng và mục đích khác nhau:</p>
                <div class="applications-list">
                    <span class="application-tag">Bảo tàng</span>
                    <span class="application-tag">Nhà nghiên cứu</span>
                    <span class="application-tag">Nhà sưu tầm</span>
                    <span class="application-tag">Thương mại</span>
                    <span class="application-tag">Giáo dục</span>
                    <span class="application-tag">Bảo tồn</span>
                </div>
            </div>

            <div class="feature-point">
                <h3><i class="fas fa-book-open"></i> Hướng dẫn sử dụng</h3>
                <p>1. Tải lên hình ảnh đồ gốm cần phân tích<br>
                    2. Hệ thống AI sẽ tự động nhận diện và phân loại<br>
                    3. Nhận kết quả chi tiết về niên đại, xuất xứ và đặc điểm<br>
                    4. Lưu trữ hoặc chia sẻ kết quả phân tích</p>
                <h4 id="guideButton" href="#" onclick="redirectToGuide()">Xem hướng dẫn chi tiết</h4>
            </div>
        </section>
    </div>

    <!-- Modal -->
    <div class="modal" id="loginPrompt">
        <div class="modal-content">
            <p>Vui lòng đăng nhập để sử dụng tính năng này</p>
            <button onclick="redirectToLogin()">Đăng Nhập</button>
        </div>
    </div>

    <!-- Contact Sidebar -->
    <div class="contact-sidebar" id="contactSidebar">
        <h3>Liên hệ với chúng tôi</h3>
        <ul>
            <li><i class="fas fa-phone"></i> <span>SĐT: 0982638519</span></li>
            <li><i class="fas fa-envelope"></i> <a href="mailto:khangkhang1111777@gmail.com">Email:
                    khangkhang1111777@gmail.com</a></li>
            <li><i class="fab fa-facebook-f"></i> <a href="https://facebook.com/ceramic" target="_blank">Facebook</a>
            </li>
            <li><i class="fab fa-instagram"></i> <a href="https://instagram.com/ceramic" target="_blank">Instagram</a>
            </li>
            <li><i class="fa-brands fa-x-twitter"></i> <a href="https://twitter.com/ceramic" target="_blank">Twitter</a>
            </li>
            <li><i class="fas fa-map-marker-alt"></i> <span>Địa chỉ: 123 Đường Gốm, TP. Hà Nội</span></li>
        </ul>
        <div class="contact-form">
            <h4>Gửi liên hệ</h4>
            <form id="contactForm" method="POST" action="<?php echo e(route('contact.submit')); ?>">
                <?php echo csrf_field(); ?>
                <h8>Nhập họ tên:</h8>
                <input type="text" name="name" placeholder="Họ tên" required>
                <h8>Nhập SĐT:</h8>
                <input type="tel" name="phone" placeholder="Số điện thoại" required>
                <h8>Nhập email:</h8>
                <input type="email" name="email" placeholder="Email" required>
                <h8>Nhập nội dung:</h8>
                <textarea name="message" placeholder="Nội dung" required></textarea>
                <button type="submit">Gửi</button>
            </form>
        </div>
    </div>

    <footer>
        <p>© 2023 Ceramic Classification System. All rights reserved.</p>
    </footer>

    <script>
        // Toggle menu hamburger
        const hamburger = document.querySelector('.hamburger');
        const navContainer = document.querySelector('.nav-container');
        const classificationLink = document.querySelector('#classificationLink');
        const contactLink = document.querySelector('#contactLink');
        const loginPrompt = document.querySelector('#loginPrompt');
        const contactSidebar = document.querySelector('#contactSidebar');
        const bannerSlides = document.querySelector('#bannerSlides');
        const slides = document.querySelectorAll('.banner-slide');
        let currentSlide = 0;

        hamburger.addEventListener('click', () => {
            navContainer.classList.toggle('active');
        });

        // Banner slideshow
        function showNextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            bannerSlides.style.transform = `translateX(-${currentSlide * 100}%)`;
        }

        // Tự động chạy banner mỗi 3 giây
        setInterval(showNextSlide, 3000);

        // Biến kiểm tra trạng thái đăng nhập
        let isAuthenticated = false;

        // Hàm kiểm tra trạng thái đăng nhập
        async function checkLoginStatus() {
            try {
                let response = await fetch("http://localhost:8000/api/check-auth", {
                    credentials: "include"
                });
                let data = await response.json();

                if (data.authenticated) {
                    isAuthenticated = true;
                    document.getElementById("loginButton").style.display = "none";
                    document.getElementById("logoutButton").style.display = "block";
                } else {
                    isAuthenticated = false;
                    document.getElementById("loginButton").style.display = "block";
                    document.getElementById("logoutButton").style.display = "none";
                }
            } catch (error) {
                console.error("Lỗi kiểm tra đăng nhập:", error);
            }
        }

        // Chuyển hướng đến trang đăng nhập
        function redirectToLogin() {
            window.location.href = "http://localhost:8000/login";
        }

        // Chuyển hướng đến trang hướng dẫn
        function redirectToGuide() {
            window.location.href = "http://localhost:8000/guide";
        }

        // Đăng xuất người dùng
        async function logout() {
            try {
                document.getElementById('logoutButton').innerHTML = '<span class="loading"></span> Processing...';
                await fetch("http://localhost:8000/api/logout", {
                    method: "POST",
                    credentials: "include"
                });
                window.location.reload();
            } catch (error) {
                console.error("Lỗi đăng xuất:", error);
                document.getElementById('logoutButton').textContent = 'Đăng xuất';
            }
        }

        // Hiển thị modal khi nhấp "Nhận diện" nếu chưa đăng nhập
        classificationLink.addEventListener('click', (e) => {
            if (!isAuthenticated) {
                e.preventDefault();
                loginPrompt.style.display = 'flex';
            }
        });

        // Đóng modal khi nhấp ra ngoài
        loginPrompt.addEventListener('click', (e) => {
            if (e.target === loginPrompt) {
                loginPrompt.style.display = 'none';
            }
        });

        // Hiển thị sidebar khi nhấp "Liên hệ"
        contactLink.addEventListener('click', (e) => {
            e.preventDefault();
            contactSidebar.classList.add('active');
        });

        // Đóng sidebar khi nhấp ra ngoài
        document.addEventListener('click', (e) => {
            if (contactSidebar.classList.contains('active') &&
                !contactSidebar.contains(e.target) &&
                e.target !== contactLink &&
                !navContainer.contains(e.target)) {
                contactSidebar.classList.remove('active');
            }
        });
        //Form liên hệ
        // Thêm vào cuối phần <script>
        const contactForm = document.getElementById('contactForm');

        contactForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(contactForm);

            try {
                const response = await fetch("<?php echo e(route('contact.submit')); ?>", {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const result = await response.json();
                if (response.ok) {
                    alert('Thông tin liên hệ đã được gửi thành công!');
                    contactForm.reset();
                    contactSidebar.classList.remove('active');
                } else {
                    alert('Có lỗi xảy ra: ' + result.message);
                }
            } catch (error) {
                console.error('Lỗi gửi liên hệ:', error);
                alert('Không thể gửi liên hệ. Vui lòng thử lại sau.');
            }
        });
        // Kiểm tra trạng thái đăng nhập khi trang tải
        window.onload = checkLoginStatus;
    </script>
</body>

</html><?php /**PATH D:\Xampp\htdocs\Ceramic_Detection\Ceramic_Detection\resources\views/index.blade.php ENDPATH**/ ?>