<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thư viện đồ gốm - Ceramic Classification</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        :root {
            --primary-color: #b3cde0;
            --secondary-color: #6497b1;
            --accent-color: #e6f0fa;
            --light-color: #f5faff;
            --dark-color: #03396c;
            --text-light: #ffffff;
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
            animation: slideDown 0.5s ease-out;
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
            animation: fadeInUp 0.6s ease-out;
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

        .nav-menu li {
            animation: fadeInUp 0.6s ease-out;
            animation-delay: calc(0.1s * var(--index));
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
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.15);
            animation: pulse 1s infinite;
        }

        .hamburger {
            display: none;
            font-size: 2rem;
            background: none;
            border: none;
            color: var(--dark-color);
            cursor: pointer;
            padding: 0.5rem;
            transition: transform 0.3s ease;
        }

        .hamburger.active {
            transform: rotate(90deg);
        }

        /* Gallery Section */
        .gallery-section {
            padding: 2rem 0;
        }

        .gallery-section h1 {
            font-size: 2.5rem;
            color: var(--dark-color);
            text-align: center;
            margin-bottom: 2rem;
            animation: fadeInUp 0.6s ease-out;
        }

        .filter-section {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 2rem;
            justify-content: center;
        }

        .filter-section select,
        .filter-section button {
            padding: 0.5rem;
            border: 1px solid var(--secondary-color);
            border-radius: 5px;
            font-size: 1rem;
            color: var(--dark-color);
            background-color: var(--accent-color);
            animation: zoomIn 0.5s ease-out;
            animation-delay: calc(0.1s * var(--index));
        }

        .filter-section button {
            padding: 0.5rem 1rem;
            background-color: var(--secondary-color);
            color: var(--text-light);
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .filter-section button:hover {
            background-color: var(--dark-color);
            transform: translateY(-2px) scale(1.05);
            animation: pulse 1s infinite;
        }

        .gallery-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .gallery-item {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            animation: fadeInUp 0.6s ease-out;
            animation-delay: calc(0.1s * var(--index));
        }

        .gallery-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .gallery-item img {
            width: auto;
            height: 200px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .gallery-item:hover img {
            transform: scale(1.05);
        }

        .gallery-content {
            padding: 1.5rem;
        }

        .gallery-content h2 {
            font-size: 1.5rem;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }

        .gallery-content p {
            font-size: 1rem;
            color: #666;
            line-height: 1.6;
            margin-bottom: 0.5rem;
        }

        .gallery-content a {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .gallery-content a:hover {
            color: var(--dark-color);
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
        }

        .pagination a,
        .pagination span {
            padding: 0.5rem 1rem;
            margin: 0 0.2rem;
            border: 1px solid var(--secondary-color);
            border-radius: 5px;
            text-decoration: none;
            color: var(--dark-color);
            transition: transform 0.3s ease, background-color 0.3s ease, color 0.3s ease;
        }

        .pagination a:hover {
            background-color: var(--secondary-color);
            color: var(--text-light);
            transform: scale(1.1);
        }

        .pagination .current {
            background-color: var(--dark-color);
            color: var(--text-light);
            border-color: var(--dark-color);
            animation: pulse 1.5s infinite;
        }

        footer {
            text-align: center;
            padding: 1.5rem;
            background-color: var(--primary-color);
            color: var(--dark-color);
            margin-top: auto;
            width: 100%;
            font-size: clamp(0.8rem, 1.5vw, 1rem);
            animation: fadeInUp 0.6s ease-out;
        }

        /* Animations */
        @keyframes slideDown {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes zoomIn {
            from {
                transform: scale(0.8);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes fadeInUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            50% {
                transform: scale(1.05);
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            }

            100% {
                transform: scale(1);
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(90deg);
            }
        }

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
                animation: slideDown 0.5s ease-out;
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

            .gallery-section {
                padding: 1.5rem;
            }

            .gallery-section h1 {
                font-size: 1.5rem;
            }

            .gallery-content h2 {
                font-size: 1.3rem;
            }

            .gallery-content p {
                font-size: 1rem;
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

            .gallery-item img {
                height: 120px;
            }

            .gallery-content h2 {
                font-size: 1.2rem;
            }

            .gallery-content p {
                font-size: 0.85rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <header>
            <div class="header-content">
                <a href="#" class="logo"><img src="http://localhost:8000/storage/ceramics/logo2.webp" alt="Logo">
                    Ceramic Classification
                </a>
                <button class="hamburger" aria-label="Toggle menu">☰</button>
                <div class="nav-container">
                    <ul class="nav-menu">
                        <li style="--index: 1;"><a href="/">Trang chủ</a></li>
                        <li style="--index: 2;"><a href="/gallery">Thư viện đồ gốm</a></li>
                        <li style="--index: 3;"><a href="/dashboard" id="classificationLink">Nhận diện</a></li>
                        <li style="--index: 4;"><a href="#market">Mua bán</a></li>
                        <!-- <li style="--index: 5;"><a href="#" id="contactLink">Liên hệ</a></li> -->
                    </ul>
                </div>
            </div>
        </header>

        <!-- Gallery Section -->
        <section class="gallery-section">
            <h1>Thư viện đồ gốm</h1>

            <!-- Filter Section -->
            <form class="filter-section" method="GET" action="{{ route('gallery') }}">
                <select name="category" style="--index: 1;">
                    <option value="">Tất cả danh mục</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
                <select name="origin" style="--index: 2;">
                    <option value="">Tất cả nguồn gốc</option>
                    @foreach ($origins as $org)
                        <option value="{{ $org }}" {{ request('origin') == $org ? 'selected' : '' }}>{{ $org }}</option>
                    @endforeach
                </select>
                <button type="submit" style="--index: 3;">Lọc</button>
            </form>

            <!-- Gallery List -->
            <div class="gallery-list">
                @forelse ($ceramics as $key => $ceramic)
                    <article class="gallery-item" style="--index: {{ $key + 1 }};">
                        <img src="{{ $ceramic->image ? asset('storage/' . $ceramic->image) : 'https://via.placeholder.com/300x200' }}"
                            alt="{{ $ceramic->name }}">
                        <div class="gallery-content">
                            <h2>{{ $ceramic->name }}</h2>
                            <p><strong>Danh mục:</strong> {{ $ceramic->category ?? 'Không có' }}</p>
                            <p><strong>Nguồn gốc:</strong> {{ $ceramic->origin ?? 'Không có' }}</p>
                            <a href="{{ route('ceramics.show', $ceramic->id) }}">Xem chi tiết</a>
                        </div>
                    </article>
                @empty
                    <p>Không tìm thấy đồ gốm nào.</p>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="pagination">
                {{ $ceramics->links() }}
            </div>
        </section>
    </div>

    <footer>
        <p>© 2023 Ceramic Classification System. All rights reserved.</p>
    </footer>

    <script>
        // Toggle menu hamburger
        const hamburger = document.querySelector('.hamburger');
        const navContainer = document.querySelector('.nav-container');
        const classificationLink = document.querySelector('#classificationLink');
        const loginPrompt = document.querySelector('#loginPrompt');
        const contactLink = document.querySelector('#contactLink');
        const contactSidebar = document.querySelector('#contactSidebar');

        hamburger.addEventListener('click', () => {
            navContainer.classList.toggle('active');
            hamburger.classList.toggle('active');
        });

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

        // Kiểm tra trạng thái đăng nhập khi trang tải
        window.onload = checkLoginStatus;
    </script>
</body>

</html>