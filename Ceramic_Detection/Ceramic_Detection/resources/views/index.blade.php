<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ceramic Classification</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Thêm Font Awesome để sử dụng icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/public.css">
    <style>
        :root {
            --primary-color: #b3cde0; /* Xanh lam nhạt */
            --secondary-color: #6497b1; /* Xanh lam trung */
            --accent-color: #e6f0fa; /* Xanh lam rất nhạt */
            --light-color: #f5faff; /* Nền nhạt */
            --dark-color: #03396c; /* Xanh lam đậm */
            --text-light: #ffffff; /* Trắng cho chữ */
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
            height: clamp(30px, 5vw, 40px);
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

        .about-section h2 {
            font-size: 2rem;
            color: var(--dark-color);
            margin-bottom: 1rem;
        }

        .about-section p {
            font-size: 1.1rem;
            color: #666;
            line-height: 1.6;
            max-width: 800px;
            margin: 0 auto;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
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
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
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
            right: -300px;
            width: 300px;
            height: 100%;
            background-color: var(--primary-color);
            color: var(--dark-color);
            padding: 2rem;
            z-index: 1500;
            transition: right 0.3s ease-in-out;
            box-shadow: -2px 0 10px rgba(0, 0, 0, 0.2);
        }

        .contact-sidebar.active {
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

            .about-section, .news-section {
                padding: 1.5rem;
            }

            .about-section h2, .news-section h1 {
                font-size: 1.5rem;
            }

            .about-section p, .news-content p {
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
                height: 25px;
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
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="header-content">
                <a href="#" class="logo">
                    <img src="logo.png" alt="Logo" style="display: none;"> <!-- Thay bằng đường dẫn logo thực tế -->
                    Ceramic Classification
                </a>
                <button class="hamburger" aria-label="Toggle menu">☰</button>
                <div class="nav-container">
                    <ul class="nav-menu">
                        <li><a href="#home">Trang chủ</a></li>
                        <li><a href="gallery">Thư viện đồ gốm</a></li>
                        <li><a href="#" id="classificationLink">Nhận diện</a></li>
                        <li><a href="#market">Mua bán</a></li>
                        <li><a href="#" id="contactLink">Liên hệ</a></li>
                    </ul>
                </div>
            </div>
        </header>
        <div class="login-section">
            <button id="loginButton" onclick="redirectToLogin()">Try It Out <i class="fa-solid fa-arrow-up-from-bracket"></i></button>
            <button id="logoutButton" onclick="logout()" style="display:none;">Đăng xuất</button>
        </div>

        <!-- Banner Section -->
        <div class="banner">
            <div class="banner-slides" id="bannerSlides">
                <img src="https://midata.io/wp-content/uploads/2024/04/fb88-khuyen-mai-banner.jpg" alt="Banner 1" class="banner-slide">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTJiERP9QRxZJ8t-wMZe8BqwxYfQOE6W__pwVKuS63JEnG2yFi041dSHZt-0r8erqiMPw&usqp=CAU" alt="Banner 2" class="banner-slide">
                <img src="https://treobangron.com.vn/wp-content/uploads/2022/09/banner-khuyen-mai-42.jpg" alt="Banner 3" class="banner-slide">
            </div>
        </div>

        <!-- News Section -->
        <section class="news-section">
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
        </section>

        <!-- About Section -->
        <section class="about-section">
            <h2>Giới thiệu về Ceramic Classification</h2>
            <p>
                Ceramic Classification là nền tảng trực tuyến hàng đầu giúp bạn khám phá và nhận diện các loại gốm sứ độc đáo. 
                Chúng tôi cung cấp công cụ phân loại thông minh, thư viện gốm phong phú, cùng không gian mua bán và chia sẻ tin tức về nghệ thuật gốm sứ. 
                Hãy cùng chúng tôi trải nghiệm hành trình tôn vinh giá trị văn hóa và lịch sử qua từng sản phẩm gốm!
            </p>
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
            <li><i class="fas fa-envelope"></i> <a href="mailto:khangkhang1111777@gmail.com">Email: khangkhang1111777@gmail.com</a></li>
            <li><i class="fab fa-facebook-f"></i> <a href="https://facebook.com/ceramic" target="_blank">Facebook</a></li>
            <li><i class="fab fa-instagram"></i> <a href="https://instagram.com/ceramic" target="_blank">Instagram</a></li>
            <li><i class="fa-brands fa-x-twitter"></i> <a href="https://twitter.com/ceramic" target="_blank">Twitter</a></li>
            <li><i class="fas fa-map-marker-alt"></i> <span>Địa chỉ: 123 Đường Gốm, TP. Hà Nội</span></li>
        </ul>
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

        // Kiểm tra trạng thái đăng nhập khi trang tải
        window.onload = checkLoginStatus;
    </script>
</body>
</html>