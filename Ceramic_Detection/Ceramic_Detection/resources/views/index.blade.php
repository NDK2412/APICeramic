<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ceramic Classification</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #6d4c41;
            --secondary-color: #8d6e63;
            --accent-color: #d7ccc8;
            --light-color: #efebe9;
            --dark-color: #3e2723;
            --success-color: #4caf50;
            --text-color: #333;
            --text-light: #f5f5f5;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: var(--light-color);
            color: var(--text-color);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
            flex: 1;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--accent-color);
            animation: slideDown 0.8s ease-out;
        }

        @keyframes slideDown {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        h1 {
            font-size: 2.5rem;
            color: var(--dark-color);
            font-weight: 600;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }

        .login-section {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            align-items: center;
            margin-top: 2rem;
        }

        .login-section button {
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 30px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        #loginButton {
            background-color: var(--primary-color);
            color: var(--text-light);
        }

        #logoutButton {
            background-color: var(--dark-color);
            color: var(--text-light);
        }

        .login-section button:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
        }

        #mainContent {
            display: none;
            animation: fadeInUp 1s ease-out;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .feature-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .upload-section, .folder-section {
            background-color: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
        }

        .upload-section:hover, .folder-section:hover {
            transform: translateY(-5px);
        }

        .upload-section {
            animation-delay: 0.2s;
        }

        .folder-section {
            animation-delay: 0.4s;
        }

        h3 {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            color: var(--primary-color);
            position: relative;
            display: inline-block;
        }

        h3::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 50px;
            height: 3px;
            background-color: var(--secondary-color);
        }

        input[type="file"], select {
            width: 100%;
            padding: 0.8rem;
            margin-bottom: 1.5rem;
            border: 2px solid var(--accent-color);
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        input[type="file"]:focus, select:focus {
            border-color: var(--primary-color);
            outline: none;
        }

        .preview-section {
            background-color: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
            text-align: center;
            transition: all 0.3s ease;
        }

        #previewImage {
            max-width: 100%;
            max-height: 400px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        #previewImage:hover {
            transform: scale(1.02);
        }

        .result-section, .chatbot-section {
            background-color: white;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }

        .result-section:hover, .chatbot-section:hover {
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
        }

        #result, #chatbotResponse {
            padding: 1rem;
            background-color: var(--light-color);
            border-radius: 8px;
            min-height: 100px;
            line-height: 1.6;
        }

        button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 0.8rem 1.8rem;
            border-radius: 30px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        button:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
        }

        button:active {
            transform: translateY(0);
        }

        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
            margin-right: 10px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        footer {
            text-align: center;
            padding: 1.5rem;
            background-color: 
            color: var(--text-light);
            margin-top: 3rem;
        }

        @media (max-width: 768px) {
            .feature-section {
                grid-template-columns: 1fr;
            }
            
            h1 {
                font-size: 2rem;
            }
            
            .container {
                padding: 1.5rem;
            }
            .login-section {
                display: flex;
                justify-content: center; /* Căn giữa theo chiều ngang */
                align-items: center;    /* Căn giữa theo chiều dọc */
                height: 100vh;          /* Chiều cao của khung chứa */
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Ceramic Classification</h1>
            <div class="login-section">
                <button id="logoutButton" onclick="logout()" style="display:none;">Đăng xuất</button>
        
        </header>
        </div>
            <div class="login-section">
            <button id="loginButton" onclick="redirectToLogin()">Đăng nhập</button>
            </div>
        <div id="mainContent">
            <div class="feature-section">
                <div class="upload-section">
                    <h3>Upload Image</h3>
                    <input type="file" id="imageInput" accept="image/*">
                    <button onclick="predictImage()">
                        <span id="predictSpinner" class="loading" style="display: none;"></span>
                        Predict
                    </button>
                </div>

                <div class="folder-section">
                    <h3>Select from Folder</h3>
                    <select id="imageSelect" onchange="loadSelectedImage()">
                        <option value="">Select an image</option>
                        <option value="ceramic1.jpg">Ceramic Sample 1</option>
                        <option value="ceramic2.jpg">Ceramic Sample 2</option>
                        <option value="ceramic3.jpg">Ceramic Sample 3</option>
                    </select>
                    <button onclick="loadSelectedImage()">Load Image</button>
                </div>
            </div>

            <div class="preview-section">
                <h3>Image Preview</h3>
                <img id="previewImage" src="" alt="Image preview" style="display: none;">
            </div>

            <div class="result-section">
                <h3>Classification Result</h3>
                <p id="result">Please upload or select an image to see the prediction</p>
            </div>

            <div class="chatbot-section">
                <h3>Ceramic Information</h3>
                <p id="chatbotResponse">Detailed information about the ceramic will appear here after prediction.</p>
            </div>
        </div>
    </div>

    <footer>
        <p>© 2023 Ceramic Classification System. All rights reserved.</p>
    </footer>

    <script>
        // Hàm kiểm tra trạng thái đăng nhập
        async function checkLoginStatus() {
            try {
                document.getElementById('predictSpinner').style.display = 'inline-block';
                let response = await fetch("http://localhost:8000/api/check-auth", {
                    credentials: "include"
                });
                let data = await response.json();

                if (data.authenticated) {
                    document.getElementById("mainContent").style.display = "block";
                    document.getElementById("loginButton").style.display = "none";
                    document.getElementById("logoutButton").style.display = "block";
                    
                    // Animation khi hiển thị nội dung chính
                    document.getElementById("mainContent").style.animation = "fadeInUp 1s ease-out";
                } else {
                    document.getElementById("mainContent").style.display = "none";
                }
            } catch (error) {
                console.error("Lỗi kiểm tra đăng nhập:", error);
            } finally {
                document.getElementById('predictSpinner').style.display = 'none';
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

        // Hiển thị ảnh đã chọn
        function loadSelectedImage() {
            const select = document.getElementById('imageSelect');
            const preview = document.getElementById('previewImage');
            
            if (select.value) {
                // Trong thực tế, bạn sẽ load ảnh từ server
                preview.src = `images/${select.value}`;
                preview.style.display = 'block';
                
                // Animation khi hiển thị ảnh
                preview.style.animation = 'fadeIn 0.5s ease-out';
                
                // Giả lập kết quả phân loại
                setTimeout(() => {
                    document.getElementById('result').textContent = `Classification: ${select.value.split('.')[0].replace('ceramic', 'Ceramic Type ')}`;
                    document.getElementById('chatbotResponse').textContent = 
                        `This is a beautiful example of ${select.value.split('.')[0].replace('ceramic', 'Ceramic Type ')}, originating from the Ming Dynasty. 
                        It features traditional blue-and-white patterns and is approximately 300 years old.`;
                }, 1000);
            } else {
                preview.style.display = 'none';
                document.getElementById('result').textContent = 'Please upload or select an image to see the prediction';
                document.getElementById('chatbotResponse').textContent = 'Detailed information about the ceramic will appear here after prediction.';
            }
        }

        // Xử lý ảnh upload
        function predictImage() {
            const input = document.getElementById('imageInput');
            const preview = document.getElementById('previewImage');
            const predictBtn = document.getElementById('predictSpinner');
            
            if (input.files && input.files[0]) {
                predictBtn.style.display = 'inline-block';
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    preview.style.animation = 'fadeIn 0.5s ease-out';
                    
                    // Giả lập quá trình xử lý
                    setTimeout(() => {
                        predictBtn.style.display = 'none';
                        document.getElementById('result').textContent = 'Classification: Ceramic Type 4 (Uploaded Sample)';
                        document.getElementById('chatbotResponse').textContent = 
                            'This uploaded ceramic piece appears to be a hand-painted vase from the Qing Dynasty. ' +
                            'It shows characteristics of famille rose porcelain with vibrant enamel colors. ' +
                            'The craftsmanship suggests it was made in the late 18th century.';
                    }, 2000);
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                alert('Please select an image first');
            }
        }

        // Kiểm tra trạng thái đăng nhập khi trang tải
        window.onload = checkLoginStatus;
    </script>
</body>
</html>