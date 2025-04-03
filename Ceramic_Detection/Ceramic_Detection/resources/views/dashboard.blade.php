<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ceramic Recognition Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        :root {
            --primary-color:rgb(38, 70, 82); /* Màu cam đậm */
            --secondary-color:rgb(118, 218, 236); /* Màu vàng nhạt */
            --light-blue: #e3f2fd;
            --white: #ffffff;
            --dark-gray: #263238;
            --light-gray: #eceff1;
            --success-color: #00c853;
            --gradient: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); /* Gradient mới */
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: var(--light-blue);
            color: var(--dark-gray);
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
            color: var(--white);
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
            color: var(--white);
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
            background: var(--white);
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            text-align: center;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .header h1 {
            font-size: 1.8rem;
            font-weight: 600;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .user-info {
            font-size: 1rem;
            color: var(--dark-gray);
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
            background: var(--white);
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .section:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .section h3 {
            font-size: 1.3rem;
            color: var(--primary-color);
            margin-bottom: 10px;
            border-bottom: 2px solid var(--light-gray);
            padding-bottom: 5px;
        }

        /* CeramicAI Section */
        .ceramic-ai {
            display: grid;
            grid-template-areas: 
                "upload preview"
                "result chatbot";
            grid-template-columns: 1fr 1fr;
            grid-template-rows: auto auto;
            gap: 15px;
            padding: 15px;
        }

        .ceramic-ai .upload-area {
            grid-area: upload;
            padding: 15px;
            background: var(--light-gray);
            border-radius: 10px;
        }

        .ceramic-ai .preview-area {
            grid-area: preview;
            padding: 15px;
            background: var(--light-gray);
            border-radius: 10px;
        }

        .ceramic-ai .result-area {
            grid-area: result;
            padding: 15px;
            background: var(--light-gray);
            border-radius: 10px;
        }

        .ceramic-ai .chatbot-area {
            grid-area: chatbot;
            padding: 15px;
            background: var(--light-gray);
            border-radius: 10px;
            max-height: 200px;
            overflow-y: auto;
            transition: background 0.3s ease;
        }

        .ceramic-ai .chatbot-area:hover {
            background: #fff8e1; /* Màu nền nhạt phù hợp với gradient mới */
        }

        .ceramic-ai .upload-area input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 2px dashed var(--primary-color);
            border-radius: 8px;
            margin-bottom: 10px;
            transition: border-color 0.3s;
        }

        .ceramic-ai .upload-area input[type="file"]:hover {
            border-color: var(--secondary-color);
        }

        .ceramic-ai .upload-area button {
            width: 100%;
            padding: 10px;
            background: var(--gradient);
            color: var(--white);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .ceramic-ai .upload-area button:hover {
            transform: scale(1.02);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .ceramic-ai .preview-area img {
            max-width: 100%;
            max-height: 200px;
            object-fit: contain;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            display: none;
            animation: zoomIn 0.5s ease;
        }

        .ceramic-ai .result-area p {
            font-size: 0.95rem;
            color: var(--dark-gray);
            line-height: 1.5;
        }

        /* Chatbot Info Styling */
        .ceramic-ai .chatbot-area .chatbot-content {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .ceramic-ai .chatbot-area .chatbot-content p {
            font-size: 0.95rem;
            color: var(--dark-gray);
            line-height: 1.6;
            margin: 0;
            padding-left: 25px;
            position: relative;
        }

        .ceramic-ai .chatbot-area .chatbot-content p::before {
            content: '\f075'; /* FontAwesome comment icon */
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            color: var(--primary-color);
            position: absolute;
            left: 0;
            top: 2px;
            font-size: 1rem;
        }

        .ceramic-ai .chatbot-area .chatbot-content p strong {
            color: var(--primary-color);
            font-weight: 600;
        }

        .ceramic-ai h4 {
            font-size: 1.1rem;
            color: var(--primary-color);
            margin-bottom: 8px;
        }

        /* Rating Section */
        .rating-section .current-rating p, 
        .rating-form p {
            font-size: 0.9rem;
            margin-bottom: 8px;
        }

        .rating-stars {
            font-size: 1.3rem;
            color: var(--primary-color);
            cursor: pointer;
        }

        .rating-stars .fas {
            color: var(--secondary-color);
        }

        .rating-form textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid var(--light-gray);
            border-radius: 8px;
            margin: 8px 0;
            resize: none;
            transition: border-color 0.3s;
        }

        .rating-form textarea:focus {
            border-color: var(--primary-color);
            outline: none;
        }

        .rating-form button {
            width: 100%;
            padding: 10px;
            background: var(--gradient);
            color: var(--white);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .rating-form button:hover {
            transform: scale(1.02);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .logout-section {
            text-align: center;
            margin-top: 15px;
        }

        .logout-section button {
            padding: 10px 25px;
            background: var(--gradient);
            color: var(--white);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .logout-section button:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes zoomIn {
            from { transform: scale(0.8); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        @media (max-width: 768px) {
            .sidebar { width: 60px; }
            .sidebar:hover { width: 200px; }
            .container { margin-left: 80px; padding: 10px; }
            .content-wrapper { gap: 10px; }
            .ceramic-ai {
                grid-template-areas: 
                    "upload"
                    "preview"
                    "result"
                    "chatbot";
                grid-template-columns: 1fr;
                grid-template-rows: auto auto auto auto;
            }
            .header h1 { font-size: 1.4rem; }
            .user-info { font-size: 0.9rem; }
        }

        @media (max-width: 480px) {
            .container { margin-left: 70px; padding: 8px; }
            .ceramic-ai .preview-area img { max-height: 150px; }
            .ceramic-ai .chatbot-area { max-height: 150px; }
            .ceramic-ai .chatbot-area .chatbot-content p {
                font-size: 0.9rem;
                padding-left: 20px;
            }
            .ceramic-ai .chatbot-area .chatbot-content p::before {
                font-size: 0.9rem;
            }
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
            <i class="fas fa-cogs"></i>
        </div>
        <ul>
            <li><a href="#" class="active" data-section="ceramic-ai"><i class="fas fa-brain"></i><span>CeramicAI</span></a></li>
            <li><a href="#" data-section="rating"><i class="fas fa-star"></i><span>Rating</span></a></li>
            <li><a href="/recharge"><i class="fas fa-wallet"></i><span>Recharge</span></a></li>
        </ul>
    </div>

    <!-- Main content -->
    <div class="container">
        <div class="header">
            <h1>Ceramic Recognition Dashboard</h1>
            <div class="user-info">
                Xin chào, {{ Auth::user()->name }}! Bạn còn <span id="tokenCount">{{ Auth::user()->tokens }}</span> lượt dự đoán.
                <br>
                <a href="/recharge">Nạp thêm lượt</a>
            </div>
        </div>

        <div class="content-wrapper">
            <div class="section ceramic-ai" id="ceramic-ai">
                <h3>CeramicAI</h3>
                <div class="upload-area">
                    <h4>Upload Image</h4>
                    <input type="file" id="imageInput" accept="image/*">
                    <button onclick="predictImage()" id="predictBtn">
                        <span id="predictSpinner" class="loading" style="display: none;"></span>
                        Dự đoán
                    </button>
                </div>
                <div class="preview-area">
                    <h4>Preview</h4>
                    <img id="previewImage" src="" alt="Image preview">
                </div>
                <div class="result-area">
                    <h4>Result</h4>
                    <p id="result">Vui lòng upload ảnh để xem kết quả.</p>
                </div>
                <div class="chatbot-area">
                    <h4>Information</h4>
                    <div class="chatbot-content" id="chatbotResponse">
                        <p>Thông tin chi tiết sẽ hiển thị tại đây.</p>
                    </div>
                </div>
            </div>

            <div class="section rating-section" id="rating">
                <h3>Rate Your Experience</h3>
                <div class="current-rating">
                    <p><strong>Your Current Rating:</strong></p>
                    <div class="rating-stars current-stars">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="fa-star {{ $i <= (Auth::user()->rating ?? 0) ? 'fas' : 'far' }}"></i>
                        @endfor
                    </div>
                    <p><strong>Feedback:</strong> {{ Auth::user()->feedback ?? 'Bạn chưa gửi phản hồi.' }}</p>
                </div>
                <div class="rating-form">
                    <p><strong>Submit New Rating:</strong></p>
                    <div class="rating-stars">
                        <i class="fa-star far" data-value="1"></i>
                        <i class="fa-star far" data-value="2"></i>
                        <i class="fa-star far" data-value="3"></i>
                        <i class="fa-star far" data-value="4"></i>
                        <i class="fa-star far" data-value="5"></i>
                    </div>
                    <textarea id="feedback" placeholder="Nhập phản hồi của bạn..." rows="4"></textarea>
                    <button onclick="submitRating()">Gửi đánh giá</button>
                </div>
            </div>
        </div>

        <div class="logout-section">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Đăng xuất</button>
            </form>
        </div>
    </div>

    <script>
        const userId = "{{ Auth::user()->id ?? 'anonymous' }}";
        let tokens = {{ Auth::user()->tokens ?? 0 }};
        console.log("User ID:", userId);
        console.log("Tokens còn lại:", tokens);

        document.getElementById('imageInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('previewImage');
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
        });

        async function predictImage() {
            const fileInput = document.getElementById('imageInput');
            const resultElement = document.getElementById('result');
            const chatbotElement = document.getElementById('chatbotResponse');
            const predictBtn = document.getElementById('predictBtn');
            const spinner = document.getElementById('predictSpinner');
            const tokenCountElement = document.getElementById('tokenCount');

            let formData = new FormData();
            if (!fileInput.files[0]) {
                resultElement.textContent = 'Vui lòng upload ảnh trước!';
                return;
            }
            formData.append('file', fileInput.files[0]);

            if (tokens <= 0) {
                resultElement.textContent = 'Bạn đã hết lượt dự đoán! Vui lòng nạp thêm.';
                chatbotElement.innerHTML = '<p>Bạn đã hết lượt dự đoán! Vui lòng nạp thêm.</p>';
                return;
            }

            predictBtn.disabled = true;
            spinner.style.display = 'inline-block';
            resultElement.textContent = 'Đang phân tích mẫu gốm...';
            chatbotElement.innerHTML = '<p>Đang nghiên cứu thông tin lịch sử...</p>';

            try {
                const predictResponse = await fetch('http://localhost:60074/predict', {
                    method: 'POST',
                    body: formData
                });
                const predictData = await predictResponse.json();

                if (predictData.error) {
                    resultElement.textContent = `Lỗi: ${predictData.error}`;
                    chatbotElement.innerHTML = '<p>Đã xảy ra lỗi trong quá trình dự đoán.</p>';
                } else {
                    const tokenResponse = await fetch('/use-token', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ userId: userId })
                    });
                    const tokenData = await tokenResponse.json();

                    if (tokenData.success) {
                        tokens = tokenData.tokens;
                        tokenCountElement.textContent = tokens;
                        resultElement.textContent = `Dự đoán: ${predictData.predicted_class}`;

                        // Format Chatbot Response
                        const llmResponse = predictData.llm_response;
                        const paragraphs = llmResponse.split('\n').filter(p => p.trim() !== '');
                        let formattedResponse = '';
                        paragraphs.forEach(paragraph => {
                            // Tô đậm các từ khóa quan trọng (ví dụ: các từ trước dấu hai chấm)
                            const formattedParagraph = paragraph.replace(/^(.*?):/g, '<strong>$1:</strong>');
                            formattedResponse += `<p>${formattedParagraph}</p>`;
                        });
                        chatbotElement.innerHTML = formattedResponse;
                    } else {
                        resultElement.textContent = 'Hết lượt dự đoán!';
                        chatbotElement.innerHTML = '<p>Hết lượt dự đoán! Vui lòng nạp thêm.</p>';
                    }
                }
            } catch (error) {
                resultElement.textContent = `Lỗi: ${error.message}`;
                chatbotElement.innerHTML = '<p>Lỗi khi kết nối với server.</p>';
            } finally {
                predictBtn.disabled = false;
                spinner.style.display = 'none';
            }
        }

        const stars = document.querySelectorAll('.rating-form .rating-stars .fa-star');
        let selectedRating = 0;

        stars.forEach(star => {
            star.addEventListener('click', function() {
                selectedRating = this.getAttribute('data-value');
                stars.forEach(s => {
                    s.classList.remove('active');
                    s.classList.remove('fas');
                    s.classList.add('far');
                    if (s.getAttribute('data-value') <= selectedRating) {
                        s.classList.add('active');
                        s.classList.add('fas');
                        s.classList.remove('far');
                    }
                });
            });
        });

        async function submitRating() {
            const feedback = document.getElementById('feedback').value;
            if (!selectedRating) {
                alert('Vui lòng chọn số sao!');
                return;
            }

            try {
                const response = await fetch('/submit-rating', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        userId: userId,
                        rating: selectedRating,
                        feedback: feedback
                    })
                });

                const data = await response.json();
                if (data.success) {
                    alert('Đánh giá của bạn đã được gửi thành công!');
                    document.getElementById('feedback').value = '';
                    stars.forEach(s => {
                        s.classList.remove('active');
                        s.classList.remove('fas');
                        s.classList.add('far');
                    });
                    selectedRating = 0;
                    location.reload();
                } else {
                    alert('Có lỗi xảy ra khi gửi đánh giá!');
                }
            } catch (error) {
                alert(`Lỗi: ${error.message}`);
            }
        }

        // Sidebar navigation
        document.querySelectorAll('.sidebar a').forEach(link => {
            link.addEventListener('click', function(e) {
                if (!this.href.includes('/recharge')) {
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
            document.getElementById('ceramic-ai').style.display = 'block';
        });
    </script>
</body>
</html>