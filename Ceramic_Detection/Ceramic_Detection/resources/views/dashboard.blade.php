<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ceramic Recognition Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4a89dc;
            --secondary-color: #5d9cec;
            --accent-color: #e6e9ed;
            --light-color: #f5f7fa;
            --dark-color: #434a54;
            --success-color: #48cfad;
            --warning-color: #ffce54;
            --error-color: #ed5565;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: var(--light-color);
            color: var(--dark-color);
            min-height: 100vh;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem;
        }

        h1 {
            text-align: center;
            margin-bottom: 2rem;
            color: var(--primary-color);
            font-size: 2.5rem;
            font-weight: 600;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
            animation: slideDown 0.8s ease-out;
        }

        @keyframes slideDown {
            from { transform: translateY(-30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .section {
            background-color: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.6s forwards;
        }

        @keyframes fadeInUp {
            to { opacity: 1; transform: translateY(0); }
        }

        .section:nth-child(1) { animation-delay: 0.2s; }
        .section:nth-child(2) { animation-delay: 0.4s; }
        .section:nth-child(3) { animation-delay: 0.6s; }
        .section:nth-child(4) { animation-delay: 0.8s; }

        .section:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }

        h3 {
            font-size: 1.3rem;
            margin-bottom: 1rem;
            color: var(--primary-color);
            position: relative;
            padding-bottom: 0.5rem;
        }

        h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background-color: var(--secondary-color);
        }

        input[type="file"] {
            width: 100%;
            padding: 0.8rem;
            margin-bottom: 1rem;
            border: 2px dashed var(--accent-color);
            border-radius: 8px;
            background-color: var(--light-color);
            transition: all 0.3s ease;
        }

        input[type="file"]:hover {
            border-color: var(--secondary-color);
            background-color: white;
        }

        button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        button:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        #previewImage {
            max-width: 100%;
            max-height: 400px;
            border-radius: 8px;
            margin-top: 1rem;
            display: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            animation: fadeIn 0.6s ease-out;
        }

        #result, #chatbotResponse {
            padding: 1rem;
            background-color: var(--light-color);
            border-radius: 8px;
            margin-top: 1rem;
            line-height: 1.6;
            border-left: 4px solid var(--secondary-color);
            animation: fadeIn 0.6s ease-out;
        }

        .logout-section {
            text-align: right;
            margin-top: 2rem;
            animation: fadeIn 1s ease-out;
        }

        .logout-section button {
            background-color: var(--error-color);
        }

        .logout-section button:hover {
            background-color: #da4453;
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

        @media (max-width: 768px) {
            .container {
                padding: 1.5rem;
            }
            
            h1 {
                font-size: 2rem;
            }
            
            .section {
                padding: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Ceramic Recognition Dashboard</h1>

        <!-- Khu vực chức năng -->
        <div id="mainContent">
            <div class="section upload-section">
                <h3>Upload Image</h3>
                <input type="file" id="imageInput" accept="image/*">
                <button onclick="predictImage()" id="predictBtn">
                    <span id="predictSpinner" class="loading" style="display: none;"></span>
                    Predict
                </button>
            </div>

            <div class="section preview-section">
                <h3>Image Preview</h3>
                <img id="previewImage" src="" alt="Image preview">
            </div>

            <div class="section result-section">
                <h3>Result</h3>
                <p id="result">Please upload an image to see the prediction</p>
            </div>

            <div class="section chatbot-section">
                <h3>Chatbot Information</h3>
                <p id="chatbotResponse">Detailed information will appear here after prediction.</p>
            </div>
        </div>

        <!-- Nút Đăng xuất -->
        <div class="logout-section">
            <button onclick="logout()">Logout</button>
        </div>
    </div>

    <script>
        // Giữ nguyên toàn bộ chức năng JavaScript từ code gốc
        function predictImage() {
            const imageInput = document.getElementById("imageInput");
            if (!imageInput.files || imageInput.files.length === 0) {
                alert("Please upload an image first.");
                return;
            }
            // Hiển thị hình ảnh đã chọn
            const file = imageInput.files[0];
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById("previewImage").src = e.target.result;
                document.getElementById("previewImage").style.display = "block";
                document.getElementById("result").innerText = "Prediction result for the uploaded image.";
            };
            reader.readAsDataURL(file);
        }

        function loadSelectedImage() {
            const imageSelect = document.getElementById("imageSelect");
            const selectedValue = imageSelect.value;

            if (selectedValue) {
                // Hiển thị hình ảnh từ danh sách
                document.getElementById("previewImage").src = selectedValue;
                document.getElementById("previewImage").style.display = "block";
                document.getElementById("result").innerText = "Prediction result for the selected image.";
            } else {
                document.getElementById("previewImage").style.display = "none";
                document.getElementById("result").innerText = "Please select an image.";
            }
        }
        window.onload = async function() {
            const response = await fetch('http://localhost:60074/images');
            const data = await response.json();
            const select = document.getElementById('imageSelect');
            data.images.forEach(image => {
                const option = document.createElement('option');
                option.value = image;
                option.textContent = image;
                select.appendChild(option);
            });

            // Kích hoạt animation khi load xong
            document.querySelectorAll('.section').forEach((section, index) => {
                section.style.animationDelay = `${index * 0.2}s`;
            });
        };

        document.getElementById('imageInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('previewImage');
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
        });

        async function loadSelectedImage() {
            const select = document.getElementById('imageSelect');
            const selectedImage = select.value;
            const preview = document.getElementById('previewImage');
            if (selectedImage) {
                preview.src = `D:/PY_Code/SecondModel/images/${selectedImage}`;
                preview.style.display = 'block';
            }
        }

        async function predictImage() {
            const fileInput = document.getElementById('imageInput');
            const select = document.getElementById('imageSelect');
            const resultElement = document.getElementById('result');
            const chatbotElement = document.getElementById('chatbotResponse');
            const predictBtn = document.getElementById('predictBtn');
            const spinner = document.getElementById('predictSpinner');

            let formData = new FormData();
            if (fileInput.files[0]) {
                formData.append('file', fileInput.files[0]);
            } else if (select.value) {
                const response = await fetch(`D:/PY_Code/SecondModel/images/${select.value}`);
                const blob = await response.blob();
                formData.append('file', blob, select.value);
            } else {
                resultElement.textContent = 'Please upload or select an image first!';
                return;
            }

            // Hiển thị loading
            predictBtn.disabled = true;
            spinner.style.display = 'inline-block';
            resultElement.textContent = 'Analyzing ceramic pattern...';
            chatbotElement.textContent = 'Researching historical context...';

            try {
                const response = await fetch('http://localhost:60074/predict', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                if (data.error) {
                    resultElement.textContent = `Error: ${data.error}`;
                    chatbotElement.textContent = 'Error occurred during prediction.';
                } else {
                    resultElement.textContent =
                        `Prediction: ${data.predicted_class} (Confidence: ${(data.confidence * 100).toFixed(2)}%)`;
                    chatbotElement.textContent = data.llm_response;
                }
            } catch (error) {
                resultElement.textContent = `Error: ${error.message}`;
                chatbotElement.textContent = 'Error occurred while connecting to the server.';
            } finally {
                predictBtn.disabled = false;
                spinner.style.display = 'none';
            }
        }
        function logout() {
            // Chuyển hướng đến trang đăng nhập
            window.location.href = "login";
        }
    </script>
</body>
</html>