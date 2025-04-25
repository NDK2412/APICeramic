<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ceramic Recognition Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        :root {
            --primary-color: rgb(38, 70, 82);
            --secondary-color: rgb(118, 218, 236);
            --light-blue: #e3f2fd;
            --white: #ffffff;
            --dark-gray: #263238;
            --light-gray: #eceff1;
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
            /* background: var(--light-blue); */
            background-image: url('/storage/images/bg.jpg');
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
            width: 300px;
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

        .sidebar a:hover,
        .sidebar a.active {
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

        .sidebar .user-name {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: var(--white);
            font-size: 1rem;
            font-weight: 500;
            transition: padding-left 0.3s;
        }

        .sidebar .user-name i {
            font-size: 1.2rem;
            min-width: 30px;
        }

        .sidebar .user-name span {
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .sidebar:hover .user-name span {
            opacity: 1;
        }

        .sidebar .user-name:hover {
            background: var(--secondary-color);
            padding-left: 30px;
        }

        .sidebar .logout-form {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: var(--white);
            font-size: 1rem;
            transition: background 0.3s, padding-left 0.3s;
        }

        .sidebar .logout-form button {
            display: flex;
            align-items: center;
            background: none;
            border: none;
            color: var(--white);
            font-size: 1rem;
            cursor: pointer;
            padding: 0;
            width: 100%;
            text-align: left;
        }

        .sidebar .logout-form i {
            font-size: 1.2rem;
            min-width: 30px;
        }

        .sidebar .logout-form span {
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .sidebar:hover .logout-form span {
            opacity: 1;
        }

        .sidebar .logout-form:hover {
            background: var(--secondary-color);
            padding-left: 30px;
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
            display: flex;
            /* Thêm display: flex */
            flex-direction: column;
            /* Sắp xếp theo cột */
            justify-content: center;
            /* Căn giữa theo chiều dọc */
            align-items: center;
            /* Căn giữa theo chiều ngang */
            min-height: 200px;
            /* Đảm bảo khu vực preview có chiều cao tối thiểu để căn giữa hiệu quả */
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
            max-height: 400px;
            overflow-y: auto;
            transition: background 0.3s ease;
        }

        .ceramic-ai .chatbot-area:hover {
            background: #fff8e1;
        }

        .ceramic-ai .upload-area input[type="file"] {
            width: 100%;
            height: 125px;
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
            display: block;
            /* Đảm bảo ảnh không bị ảnh hưởng bởi inline styles */
            animation: zoomIn 0.5s ease;
        }

        .ceramic-ai .preview-area h4 {
            text-align: left;
            /* Căn trái tiêu đề, giống Upload Image và Result */
            margin-bottom: 10px;
            /* Khoảng cách giữa tiêu đề và ảnh */
        }

        .ceramic-ai .preview-area .image-container {
            flex: 1;
            /* Chiếm toàn bộ không gian còn lại */
            display: flex;
            /* Sử dụng flex để căn giữa ảnh */
            justify-content: center;
            /* Căn giữa theo chiều ngang */
            align-items: center;
            /* Căn giữa theo chiều dọc */
        }

        .ceramic-ai .result-area p {
            font-size: 0.95rem;
            color: var(--dark-gray);
            line-height: 1.5;
        }

        .ceramic-ai .chatbot-area .chatbot-content {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .ceramic-ai .chatbot-area .chatbot-content p {
            font-size: 0.95rem;
            color: var(--dark-color);
            line-height: 1.6;
            margin: 0;
            padding-left: 25px;
            position: relative;
        }

        .ceramic-ai .chatbot-area .chatbot-content p::before {
            content: '\f075';
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

        /* History Section Styling */
        .history-section table {
            width: 100%;
            border-collapse: collapse;
        }

        .history-section th,
        .history-section td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid var(--light-gray);
        }

        .history-section th {
            background: var(--primary-color);
            color: var(--white);
        }

        .history-section tr:hover {
            background: var(--light-gray);
        }

        .history-section img {
            max-width: 100px;
            height: auto;
        }

        .history-section .view-info-btn {
            padding: 6px 12px;
            background: var(--gradient);
            color: var(--white);
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .history-section .view-info-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        /* Pagination Styling */
        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 5px;
        }

        .pagination .page-item {
            list-style: none;
        }

        .pagination .page-link {
            padding: 8px 12px;
            background: var(--light-gray);
            color: var(--dark-gray);
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s;
        }

        .pagination .page-link:hover {
            background: var(--primary-color);
            color: var(--white);
        }

        .pagination .active .page-link {
            background: var(--primary-color);
            color: var(--white);
        }

        /* Popup Styling */
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
            background: var(--white);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            width: 1000px;
            max-width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }

        .popup h3 {
            font-size: 1.4rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .popup p {
            font-size: 0.95rem;
            color: var(--dark-gray);
            line-height: 1.6;
            margin-bottom: 10px;
        }

        .popup p strong {
            color: var(--primary-color);
        }

        .popup button {
            width: 100%;
            padding: 10px;
            background: var(--gradient);
            color: var(--white);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .popup button:hover {
            transform: scale(1.02);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        input:his {
            height: 40px;
            margin-bottom: 15px;

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

            .content-wrapper {
                gap: 10px;
            }

            .ceramic-ai {
                grid-template-areas:
                    "upload"
                    "preview"
                    "result"
                    "chatbot";
                grid-template-columns: 1fr;
                grid-template-rows: auto auto auto auto;
            }

            .header h1 {
                font-size: 1.4rem;
            }

            .user-info {
                font-size: 0.9rem;
            }

            .history-section table {
                font-size: 0.85rem;
            }

            .history-section img {
                max-width: 80px;
            }

            .popup {
                width: 90%;
            }
        }

        @media (max-width: 480px) {
            .container {
                margin-left: 70px;
                padding: 8px;
            }

            .ceramic-ai .preview-area img {
                max-height: 150px;
            }

            .ceramic-ai .chatbot-area {
                max-height: 150px;
            }

            .ceramic-ai .chatbot-area .chatbot-content p {
                font-size: 0.9rem;
                padding-left: 20px;
            }

            .ceramic-ai .chatbot-area .chatbot-content p::before {
                font-size: 0.9rem;
            }
        }

        .upload-area.dragover {
            border-color: var(--secondary-color);
            background: rgba(118, 218, 236, 0.1);
        }

        input#historySearch {
            width: 100%;
            /* Chiều rộng của input */
            padding: 10px;
            /* Khoảng cách nội dung so với biên */
            border: 2px solid #ccc;
            /* Viền màu xám nhạt */
            border-radius: 5px;
            /* Góc bo tròn cho input */
            font-size: 16px;
            /* Kích thước chữ */
            outline: none;
            /* Loại bỏ viền mặc định khi input được chọn */
            margin-bottom: 15px;
        }

        .dark-theme {
            --primary-color: #90caf9;
            --secondary-color: #42a5f5;
            --light-blue: #0d47a1;
            --white: #263238;
            --dark-gray: #e0e0e0;
            --light-gray: #37474f;
        }

        /* Thêm các animation mới */
        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.05);
                opacity: 0.8;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(20px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadePulse {
            0% {
                opacity: 0.5;
            }

            50% {
                opacity: 1;
            }

            100% {
                opacity: 0.5;
            }
        }

        /* Áp dụng animation cho các phần tử khi đang xử lý */
        .processing {
            animation: pulse 1.5s infinite ease-in-out;
        }

        .result-processing {
            animation: fadePulse 1.5s infinite ease-in-out;
        }

        .chatbot-processing {
            animation: fadePulse 1.5s infinite ease-in-out;
        }

        .result-loaded {
            animation: slideUp 0.5s ease-in-out;
        }

        /* All Users Rating Section */
        .all-users-rating {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid var(--light-gray);
        }

        .all-users-rating h4 {
            font-size: 1.1rem;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .all-users-rating table {
            width: 100%;
            border-collapse: collapse;
        }

        .all-users-rating th,
        .all-users-rating td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid var(--light-gray);
        }

        .all-users-rating th {
            background: var(--primary-color);
            color: var(--white);
        }

        .all-users-rating tr:hover {
            background: var(--light-gray);
        }

        .all-users-rating td .fa-star.fas {
            color: var(--secondary-color);
        }

        .all-users-rating p {
            font-size: 0.95rem;
            color: var(--dark-gray);
        }

        /* Rating Filter */
        .rating-filter {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .rating-filter label {
            font-size: 0.95rem;
            color: var(--dark-gray);
        }

        .rating-filter select {
            padding: 8px;
            border: 1px solid var(--light-gray);
            border-radius: 5px;
            font-size: 0.95rem;
            cursor: pointer;
            transition: border-color 0.3s;
        }

        .rating-filter select:focus {
            border-color: var(--primary-color);
            outline: none;
        }

        /* All Users Rating Table */
        .all-users-rating table {
            width: 100%;
            border-collapse: collapse;
        }

        .all-users-rating th,
        .all-users-rating td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid var(--light-gray);
        }

        .all-users-rating th {
            background: var(--primary-color);
            color: var(--white);
        }

        .all-users-rating tr:hover {
            background: var(--light-gray);
        }

        .all-users-rating td .fa-star.fas {
            color: var(--secondary-color);
        }

        /* Pagination */
        .pagination {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 5px;
        }

        .pagination .page-link {
            padding: 8px 12px;
            background: var(--light-gray);
            color: var(--dark-gray);
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.3s;
        }

        .pagination .page-link:hover {
            background: var(--primary-color);
            color: var(--white);
        }

        .pagination .active {
            background: var(--primary-color);
            color: var(--white);
        }




        /* Rating Filter Container */
        .rating-filter {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            padding: 10px 15px;
            background: linear-gradient(135deg, var(--white), var(--light-gray));
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .rating-filter:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
        }

        /* Label */
        .rating-filter label {
            font-size: 1rem;
            font-weight: 500;
            color: var(--primary-color);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: black;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            position: relative;
            padding-left: 25px;
            display: flex;
            align-items: center;
            animation: fadeIn 0.5s ease-in;
        }

        .rating-filter label::before {
            content: '\f005';
            /* Font Awesome star icon */
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            color: var(--secondary-color);
            font-size: 1.2rem;
            position: absolute;
            left: 0;
            animation: pulseStar 2s infinite ease-in-out;
        }

        /* Select Dropdown */
        .rating-filter select {
            appearance: none;
            /* Xóa style mặc định của trình duyệt */
            padding: 10px 40px 10px 15px;
            font-size: 0.95rem;
            color: var(--dark-gray);
            background: var(--white);
            border: 1px solid var(--light-gray);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%26263238' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .rating-filter select:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 8px rgba(38, 70, 82, 0.3);
        }

        .rating-filter select:hover {
            background-color: var(--light-gray);
            border-color: var(--secondary-color);
        }

        .rating-filter select option {
            padding: 10px;
            color: var(--dark-gray);
            background: var(--white);
        }

        h2 a u {
            color: green;
        }

        /* Upload mode selector */
        .upload-mode-selector {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-bottom: 15px;
        }

        .mode-btn {
            padding: 8px 20px;
            border: none;
            border-radius: 20px;
            background: var(--light-gray);
            color: var(--dark-gray);
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .mode-btn.active,
        .mode-btn:hover {
            background: var(--gradient);
            color: var(--white);
            transform: scale(1.05);
        }

        /* Upload mode */
        .upload-mode {
            display: none;
        }

        .upload-mode.active {
            display: block;
        }

        .upload-area input[type="file"] {
            width: 100%;
            height: 125px;
            padding: 10px;
            border: 2px dashed var(--primary-color);
            border-radius: 8px;
            margin-bottom: 15px;
            cursor: pointer;
            background: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
        }

        .upload-area input[type="file"]:hover {
            border-color: var(--secondary-color);
            background: rgba(118, 218, 236, 0.1);
        }

        /* Camera mode */
        .camera-mode {
            position: relative;
            text-align: center;
        }

        #cameraStream {
            width: 100%;
            max-height: 200px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid var(--primary-color);
            background: var(--light-gray);
        }

        .capture-btn {
            padding: 10px 20px;
            background: var(--gradient);
            color: var(--white);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 10px;
            transition: all 0.3s ease;
        }

        .capture-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        /* Animation cho camera */
        @keyframes cameraFadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .camera-mode.active {
            animation: cameraFadeIn 0.5s ease;
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
            <i class="fas fa-microchip fa-spin-hover"></i> <!-- Icon công nghệ AI -->
        </div>
        <div class="user-name">
            <i class="fas fa-user-circle"></i>
            <span>{{ Auth::user()->name }}</span>
        </div>
        <ul>
            <li><a href="#" class="active" data-section="ceramic-ai"><i
                        class="fas fa-vial"></i><span>CeramicAI</span></a></li> <!-- Icon gốm sứ -->
            <li><a href="#" data-section="history"><i class="fas fa-clock-rotate-left"></i><span>Lịch sử</span></a></li>
            <!-- Icon lịch sử -->
            <li><a href="#" data-section="rating"><i class="fas fa-star-half-alt"></i><span>Rating</span></a></li>
            <!-- Icon đánh giá -->
            <li><a href="/recharge"><i class="fas fa-coins"></i><span>Recharge</span></a></li> <!-- Icon tiền -->
            <li><a href="#" onclick="toggleTheme()"><i class="fas fa-moon"></i><span>Đổi Theme</span></a></li>
            <!-- Icon theme -->
            <li><a href="#" onclick="showChangeNamePopup()"><i class="fas fa-pen-to-square"></i><span>Đổi tên</span></a>
            </li> <!-- Icon chỉnh sửa -->
            <li>
                <form method="POST" action="{{ route('logout') }}" class="logout-form">
                    @csrf
                    <button type="submit">
                        <i class="fas fa-right-from-bracket"></i>
                        <span>Đăng xuất</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>

    <!-- Main content -->
    <!-- Main content -->
    <div class="container">
        <div class="header">
            <h1>Ceramic Recognition Dashboard</h1>
            <div class="user-info">
                <h2>
                    Xin chào, {{ Auth::user()->name }}! Bạn còn <span id="tokenCount">{{ Auth::user()->tokens }}</span>
                    lượt dự đoán.
                    <br>
                    <a href="/recharge"><u>Nạp thêm lượt</u></a>
                </h2>
            </div>
        </div>

        <div class="content-wrapper">
            <div class="section ceramic-ai" id="ceramic-ai">
                <h3><i class="fas fa-vial me-2"></i> CeramicAI</h3>
                <h4>Chọn Ảnh</h4>
                <div class="upload-area" ondrop="handleDrop(event)" ondragover="handleDragOver(event)"
                    ondragleave="handleDragLeave(event)">
                    <div class="upload-mode-selector">
                        <button class="mode-btn active" onclick="selectMode('upload')"><i
                                class="fas fa-upload me-1"></i> Tải ảnh</button>
                        <button class="mode-btn" onclick="selectMode('camera')"><i class="fas fa-camera me-1"></i> Chụp
                            ảnh</button>
                    </div>
                    <!-- Upload file -->
                    <div id="uploadMode" class="upload-mode active">
                        <input type="file" id="imageInput" accept="image/*">
                    </div>
                    <!-- Camera -->
                    <div id="cameraMode" class="upload-mode camera-mode">
                        <video id="cameraStream" autoplay playsinline></video>
                        <canvas id="cameraCanvas" style="display: none;"></canvas>
                        <button class="capture-btn" onclick="capturePhoto()"><i class="fas fa-camera-retro me-1"></i>
                            Chụp</button>
                    </div>
                    <button onclick="predictImage()" id="predictBtn" class="predict-btn">
                        <span id="predictSpinner" class="loading" style="display: none;"></span>
                        <i class="fas fa-brain me-1"></i> Dự đoán
                    </button>
                </div>
                <h4>Preview</h4>
                <div class="preview-area">
                    <div class="image-container">
                        <img id="previewImage" src="" alt="Image preview">
                    </div>
                </div>
                <h4>Result</h4>
                <div class="result-area">
                    <p id="result"><i class="fas fa-brain me-1"></i> Vui lòng upload ảnh để xem kết quả.</p>
                </div>
                <h4>Information</h4>
                <div class="chatbot-area">
                    <div class="chatbot-content" id="chatbotResponse">
                        <p><i class="#"></i> Thông tin chi tiết sẽ hiển thị tại đây.</p>
                    </div>
                </div>
            </div>

            <div class="section history-section" id="history" style="display: none;">
                <h3>Lịch Sử Nhận Diện</h3>
                <input type="text" id="historySearch" placeholder="Tìm kiếm theo kết quả..." onkeyup="filterHistory()">
                @if ($classifications->isEmpty())
                    <p>Không có lịch sử nhận diện nào.</p>
                @else
                    <table>
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Ảnh</th>
                                <th>Kết quả</th>
                                <th>Thời gian</th>
                                <th>Thông tin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($classifications as $classification)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    <!-- <td>{{ $classification->id }}</td> -->
                                    <td>
                                        <img src="{{ url($classification->image_path) }}" alt="Image"
                                            style="max-width: 100px; border-radius: 5px;">
                                    </td>
                                    <td>{{ $classification->result }}</td>
                                    <td>{{ $classification->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <button class="view-info-btn" onclick="showInfoPopup({{ $classification->id }})">
                                            <i class="fas fa-eye"></i> Xem chi tiết
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                @endif
            </div>

            <div class="section rating-section" id="rating" style="display: none;">
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
                <!-- Khu vực hiển thị rating của tất cả người dùng -->
                <div class="all-users-rating">
                    <h4>Đánh Giá Từ Người Dùng Khác</h4>
                    <!-- Bộ lọc theo số sao -->
                    <div class="rating-filter">
                        <label for="ratingFilter">Lọc theo số sao: </label>
                        <select id="ratingFilter" onchange="filterAndPaginateRatings()">
                            <option value="all">Tất cả</option>
                            <option value="1">1 Sao</option>
                            <option value="2">2 Sao</option>
                            <option value="3">3 Sao</option>
                            <option value="4">4 Sao</option>
                            <option value="5">5 Sao</option>
                        </select>
                    </div>
                    <!-- Bảng hiển thị rating -->
                    <table id="ratingsTable">
                        <thead>
                            <tr>
                                <th>Tên Người Dùng</th>
                                <th>Đánh Giá</th>
                                <th>Phản Hồi</th>
                            </tr>
                        </thead>
                        <tbody id="ratingsBody">
                            <!-- Nội dung sẽ được render bằng JavaScript -->
                        </tbody>
                    </table>
                    <!-- Thông báo khi không có dữ liệu -->
                    <p id="noRatingsMessage" style="display: none;">Chưa có đánh giá nào từ người dùng khác.</p>
                    <!-- Phân trang -->
                    <div class="pagination" id="pagination">
                        <!-- Các nút phân trang sẽ được thêm bằng JavaScript -->
                    </div>
                </div>
            </div>

            <!-- Popup hiển thị thông tin chi tiết -->
            <div class="popup-overlay" id="infoPopupOverlay" onclick="hideInfoPopup()"></div>
            <div class="popup" id="infoPopup">
                <h3>Thông tin chi tiết</h3>
                <div id="infoPopupContent">
                    <!-- Nội dung sẽ được thêm bằng JavaScript -->
                </div>
                <button onclick="hideInfoPopup()">Đóng</button>
            </div>
            <!-- Popup đổi tên -->
            <div class="popup-overlay" id="changeNamePopupOverlay" onclick="hideChangeNamePopup()"></div>
            <div class="popup" id="changeNamePopup">
                <h3>Đổi Tên Người Dùng</h3>
                <div id="changeNameContent">
                    <p>Tên hiện tại: <strong>{{ Auth::user()->name }}</strong></p>
                    <input type="text" id="newName" placeholder="Nhập tên mới"
                        style="width: 100%; padding: 10px; margin: 10px 0; border-radius: 5px; border: 1px solid var(--light-gray);">
                    <p id="changeNameError" style="color: #f44336; display: none;"></p>
                </div>
                <button onclick="submitNewName()">Lưu</button>
            </div>
            <script>
                const userId = "{{ Auth::user()->id ?? 'anonymous' }}";
                let tokens = {{ Auth::user()->tokens ?? 0 }};
                console.log("User ID:", userId);
                console.log("Tokens còn lại:", tokens);

                document.getElementById('imageInput').addEventListener('change', function (e) {
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
                    // Bật animation khi bắt đầu xử lý
                    predictBtn.disabled = true;
                    predictBtn.classList.add('processing'); // Animation pulse cho nút
                    spinner.style.display = 'inline-block';
                    resultElement.textContent = 'Đang phân tích mẫu gốm...';
                    resultElement.classList.add('result-processing'); // Animation nhấp nháy cho Result
                    chatbotElement.innerHTML = '<p>Đang nghiên cứu thông tin lịch sử...</p>';
                    chatbotElement.classList.add('chatbot-processing'); // Animation nhấp nháy cho Chatbot
                    if (!fileInput.files[0]) {
                        resultElement.textContent = 'Vui lòng upload ảnh trước!';
                        return;
                    }

                    const formData = new FormData();
                    formData.append('file', fileInput.files[0]);
                    formData.append('_token', '{{ csrf_token() }}');

                    predictBtn.disabled = true;
                    spinner.style.display = 'inline-block';
                    resultElement.textContent = 'Đang phân tích mẫu gốm...';
                    chatbotElement.innerHTML = '<p>Đang nghiên cứu thông tin lịch sử...</p>';

                    try {
                        const response = await fetch('{{ route('predict.image') }}', {
                            method: 'POST',
                            body: formData
                        });

                        const data = await response.json();
                        // Xóa animation khi xử lý xong
                        predictBtn.classList.remove('processing');
                        resultElement.classList.remove('result-processing');
                        chatbotElement.classList.remove('chatbot-processing');
                        if (data.error) {
                            resultElement.textContent = `Lỗi: ${data.error}`;
                            chatbotElement.innerHTML = `<p>${data.error}</p>`;
                        } else {
                            tokenCountElement.textContent = data.tokens;
                            resultElement.textContent = `Dự đoán: ${data.predicted_class}`;
                            resultElement.classList.add('result-loaded');
                            const llmResponse = data.llm_response;
                            const paragraphs = llmResponse.split('\n').filter(p => p.trim() !== '');
                            let formattedResponse = '';
                            paragraphs.forEach(paragraph => {
                                const formattedParagraph = paragraph.replace(/^(.*?):/g, '<strong>$1:</strong>');
                                formattedResponse += `<p>${formattedParagraph}</p>`;
                            });
                            chatbotElement.innerHTML = formattedResponse;
                            chatbotElement.classList.add('result-loaded');
                        }
                    } catch (error) {
                        predictBtn.classList.remove('processing');
                        resultElement.classList.remove('result-processing');
                        chatbotElement.classList.remove('chatbot-processing');
                        resultElement.textContent = `Lỗi: ${error.message}`;
                        chatbotElement.innerHTML = '<p>Lỗi khi kết nối với server.</p>';
                    } finally {
                        predictBtn.disabled = false;
                        spinner.style.display = 'none';
                        // Xóa class result-loaded sau khi animation hoàn tất (để có thể tái sử dụng)
                        setTimeout(() => {
                            resultElement.classList.remove('result-loaded');
                            chatbotElement.classList.remove('result-loaded');
                        }, 500); // Thời gian khớp với animation slideUp
                    }
                }

                const stars = document.querySelectorAll('.rating-form .rating-stars .fa-star');
                let selectedRating = 0;

                stars.forEach(star => {
                    star.addEventListener('click', function () {
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

                async function showInfoPopup(classificationId) {
                    const popup = document.getElementById('infoPopup');
                    const overlay = document.getElementById('infoPopupOverlay');
                    const content = document.getElementById('infoPopupContent');

                    try {
                        const response = await fetch(`/classification/${classificationId}/info`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        const data = await response.json();

                        if (data.llm_response) {
                            const paragraphs = data.llm_response.split('\n').filter(p => p.trim() !== '');
                            let formattedResponse = '';
                            paragraphs.forEach(paragraph => {
                                const formattedParagraph = paragraph.replace(/^(.*?):/g, '<strong>$1:</strong>');
                                formattedResponse += `<p>${formattedParagraph}</p>`;
                            });
                            content.innerHTML = formattedResponse;
                        } else {
                            content.innerHTML = '<p>Không có thông tin chi tiết.</p>';
                        }
                    } catch (error) {
                        content.innerHTML = '<p>Lỗi khi tải thông tin chi tiết.</p>';
                        console.error('Error fetching classification info:', error);
                    }

                    popup.style.display = 'block';
                    overlay.style.display = 'block';
                }

                function hideInfoPopup() {
                    const popup = document.getElementById('infoPopup');
                    const overlay = document.getElementById('infoPopupOverlay');
                    popup.style.display = 'none';
                    overlay.style.display = 'none';
                }

                document.querySelectorAll('.sidebar a').forEach(link => {
                    link.addEventListener('click', function (e) {
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
                function handleDragOver(e) {
                    e.preventDefault();
                    e.target.classList.add('dragover');
                }

                function handleDragLeave(e) {
                    e.target.classList.remove('dragover');
                }

                function handleDrop(e) {
                    e.preventDefault();
                    e.target.classList.remove('dragover');
                    const file = e.dataTransfer.files[0];
                    if (file && file.type.startsWith('image/')) {
                        document.getElementById('imageInput').files = e.dataTransfer.files;
                        const preview = document.getElementById('previewImage');
                        preview.src = URL.createObjectURL(file);
                        preview.style.display = 'block';
                    } else {
                        alert('Vui lòng kéo thả file ảnh!');
                    }
                }
                function filterHistory() {
                    const query = document.getElementById('historySearch').value.toLowerCase();
                    const rows = document.querySelectorAll('#history table tbody tr');
                    rows.forEach(row => {
                        const result = row.cells[2].textContent.toLowerCase();
                        row.style.display = result.includes(query) ? '' : 'none';
                    });
                }
                //Đổi theme
                function toggleTheme() {
                    document.body.classList.toggle('dark-theme');
                    localStorage.setItem('theme', document.body.classList.contains('dark-theme') ? 'dark' : 'light');
                }
                if (localStorage.getItem('theme') === 'dark') document.body.classList.add('dark-theme');
                // Hiển thị popup đổi tên
                function showChangeNamePopup() {
                    const popup = document.getElementById('changeNamePopup');
                    const overlay = document.getElementById('changeNamePopupOverlay');
                    popup.style.display = 'block';
                    overlay.style.display = 'block';
                    document.getElementById('newName').value = ''; // Xóa nội dung input khi mở
                    document.getElementById('changeNameError').style.display = 'none'; // Ẩn thông báo lỗi
                }

                // Ẩn popup đổi tên
                function hideChangeNamePopup() {
                    const popup = document.getElementById('changeNamePopup');
                    const overlay = document.getElementById('changeNamePopupOverlay');
                    popup.style.display = 'none';
                    overlay.style.display = 'none';
                }

                // Gửi yêu cầu đổi tên
                async function submitNewName() {
                    const newName = document.getElementById('newName').value.trim();
                    const errorElement = document.getElementById('changeNameError');

                    if (!newName) {
                        errorElement.textContent = 'Vui lòng nhập tên mới!';
                        errorElement.style.display = 'block';
                        return;
                    }

                    if (newName.length < 3) {
                        errorElement.textContent = 'Tên phải có ít nhất 3 ký tự!';
                        errorElement.style.display = 'block';
                        return;
                    }

                    try {
                        const response = await fetch('/change-name', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                userId: userId,
                                name: newName
                            })
                        });

                        const data = await response.json();
                        if (data.success) {
                            alert('Tên của bạn đã được cập nhật thành công!');
                            document.querySelector('.user-name span').textContent = newName; // Cập nhật tên trong sidebar
                            document.querySelector('.user-info').innerHTML = `Xin chào, ${newName}! Bạn còn <span id="tokenCount">${tokens}</span> lượt dự đoán.<br><a href="/recharge">Nạp thêm lượt</a>`; // Cập nhật tên trong header
                            hideChangeNamePopup();
                        } else {
                            errorElement.textContent = data.error || 'Có lỗi xảy ra khi đổi tên!';
                            errorElement.style.display = 'block';
                        }
                    } catch (error) {
                        errorElement.textContent = `Lỗi: ${error.message}`;
                        errorElement.style.display = 'block';
                    }
                }
                // Dữ liệu rating từ server
                const allUserRatings = @json($allUserRatings);
                const ITEMS_PER_PAGE = 10; // Giới hạn 10 người dùng mỗi trang
                let currentPage = 1;
                let filteredRatings = allUserRatings; // Danh sách ratings sau khi lọc

                // Hàm render bảng ratings
                function renderRatings(page = 1) {
                    const tbody = document.getElementById('ratingsBody');
                    const noRatingsMessage = document.getElementById('noRatingsMessage');
                    const start = (page - 1) * ITEMS_PER_PAGE;
                    const end = start + ITEMS_PER_PAGE;
                    const paginatedRatings = filteredRatings.slice(start, end);

                    // Xóa nội dung bảng
                    tbody.innerHTML = '';

                    // Kiểm tra nếu không có dữ liệu
                    if (filteredRatings.length === 0) {
                        noRatingsMessage.style.display = 'block';
                        document.getElementById('ratingsTable').style.display = 'none';
                        document.getElementById('pagination').style.display = 'none';
                        return;
                    }

                    // Hiển thị bảng và ẩn thông báo
                    noRatingsMessage.style.display = 'none';
                    document.getElementById('ratingsTable').style.display = 'table';
                    document.getElementById('pagination').style.display = 'flex';

                    // Render các dòng trong bảng
                    paginatedRatings.forEach(user => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                <td>${user.name}</td>
                <td>
                    ${Array.from({ length: 5 }, (_, i) =>
                            `<i class="fa-star ${i < user.rating ? 'fas' : 'far'}"></i>`
                        ).join('')}
                </td>
                <td>${user.feedback || 'Không có phản hồi.'}</td>
            `;
                        tbody.appendChild(row);
                    });

                    // Render phân trang
                    renderPagination();
                }

                // Hàm render phân trang
                function renderPagination() {
                    const pagination = document.getElementById('pagination');
                    const totalPages = Math.ceil(filteredRatings.length / ITEMS_PER_PAGE);

                    // Xóa nội dung phân trang cũ
                    pagination.innerHTML = '';

                    // Thêm nút Previous
                    const prevLink = document.createElement('span');
                    prevLink.className = 'page-link';
                    prevLink.textContent = '«';
                    prevLink.onclick = () => {
                        if (currentPage > 1) {
                            currentPage--;
                            renderRatings(currentPage);
                        }
                    };
                    pagination.appendChild(prevLink);

                    // Thêm các nút số trang
                    for (let i = 1; i <= totalPages; i++) {
                        const pageLink = document.createElement('span');
                        pageLink.className = `page-link ${i === currentPage ? 'active' : ''}`;
                        pageLink.textContent = i;
                        pageLink.onclick = () => {
                            currentPage = i;
                            renderRatings(currentPage);
                        };
                        pagination.appendChild(pageLink);
                    }

                    // Thêm nút Next
                    const nextLink = document.createElement('span');
                    nextLink.className = 'page-link';
                    nextLink.textContent = '»';
                    nextLink.onclick = () => {
                        if (currentPage < totalPages) {
                            currentPage++;
                            renderRatings(currentPage);
                        }
                    };
                    pagination.appendChild(nextLink);
                }

                // Hàm lọc theo số sao
                function filterAndPaginateRatings() {
                    const filterValue = document.getElementById('ratingFilter').value;
                    currentPage = 1; // Reset về trang 1 khi lọc

                    if (filterValue === 'all') {
                        filteredRatings = allUserRatings;
                    } else {
                        filteredRatings = allUserRatings.filter(user => user.rating === parseInt(filterValue));
                    }

                    renderRatings(currentPage);
                }

                // Khởi tạo khi tải trang
                document.addEventListener('DOMContentLoaded', () => {
                    renderRatings(currentPage);
                });
                // Các hàm khác giữ nguyên...
                // Quản lý chế độ upload/camera
                let currentMode = 'upload';
                let stream = null;

                function selectMode(mode) {
                    currentMode = mode;
                    const uploadMode = document.getElementById('uploadMode');
                    const cameraMode = document.getElementById('cameraMode');
                    const modeButtons = document.querySelectorAll('.mode-btn');

                    // Cập nhật giao diện
                    modeButtons.forEach(btn => btn.classList.remove('active'));
                    document.querySelector(`.mode-btn[onclick="selectMode('${mode}')"]`).classList.add('active');

                    if (mode === 'upload') {
                        uploadMode.classList.add('active');
                        cameraMode.classList.remove('active');
                        stopCamera();
                    } else {
                        uploadMode.classList.remove('active');
                        cameraMode.classList.add('active');
                        startCamera();
                    }
                }

                // Khởi động camera
                async function startCamera() {
                    const video = document.getElementById('cameraStream');
                    try {
                        stream = await navigator.mediaDevices.getUserMedia({
                            video: { facingMode: 'environment' } // Ưu tiên camera sau trên mobile
                        });
                        video.srcObject = stream;
                    } catch (error) {
                        console.error('Lỗi khi truy cập camera:', error);
                        alert('Không thể truy cập camera. Vui lòng kiểm tra quyền hoặc thử lại.');
                        selectMode('upload');
                    }
                }

                // Dừng camera
                function stopCamera() {
                    if (stream) {
                        stream.getTracks().forEach(track => track.stop());
                        stream = null;
                        document.getElementById('cameraStream').srcObject = null;
                    }
                }

                // Chụp ảnh từ camera
                function capturePhoto() {
                    const video = document.getElementById('cameraStream');
                    const canvas = document.getElementById('cameraCanvas');
                    const preview = document.getElementById('previewImage');

                    // Thiết lập canvas
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    canvas.getContext('2d').drawImage(video, 0, 0);

                    // Chuyển canvas thành blob để gửi
                    canvas.toBlob(blob => {
                        const file = new File([blob], 'captured_photo.jpg', { type: 'image/jpeg' });
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        document.getElementById('imageInput').files = dataTransfer.files;

                        // Cập nhật preview
                        preview.src = URL.createObjectURL(file);
                        preview.style.display = 'block';

                        // Tắt camera sau khi chụp
                        stopCamera();
                        selectMode('upload');
                    }, 'image/jpeg', 0.95);
                }

                // Sửa hàm predictImage để hỗ trợ cả hai chế độ
                async function predictImage() {
                    const fileInput = document.getElementById('imageInput');
                    const resultElement = document.getElementById('result');
                    const chatbotElement = document.getElementById('chatbotResponse');
                    const predictBtn = document.getElementById('predictBtn');
                    const spinner = document.getElementById('predictSpinner');
                    const tokenCountElement = document.getElementById('tokenCount');

                    // Kiểm tra file
                    if (!fileInput.files[0]) {
                        resultElement.textContent = 'Vui lòng chọn hoặc chụp ảnh trước!';
                        predictBtn.classList.remove('processing');
                        spinner.style.display = 'none';
                        return;
                    }

                    // Bật animation khi bắt đầu xử lý
                    predictBtn.disabled = true;
                    predictBtn.classList.add('processing');
                    spinner.style.display = 'inline-block';
                    resultElement.textContent = 'Đang phân tích mẫu gốm...';
                    resultElement.classList.add('result-processing');
                    chatbotElement.innerHTML = '<p>Đang nghiên cứu thông tin lịch sử...</p>';
                    chatbotElement.classList.add('chatbot-processing');

                    const formData = new FormData();
                    formData.append('file', fileInput.files[0]);
                    formData.append('_token', '{{ csrf_token() }}');

                    try {
                        const response = await fetch('{{ route('predict.image') }}', {
                            method: 'POST',
                            body: formData
                        });

                        const data = await response.json();
                        predictBtn.classList.remove('processing');
                        resultElement.classList.remove('result-processing');
                        chatbotElement.classList.remove('chatbot-processing');

                        if (data.error) {
                            resultElement.textContent = `Lỗi: ${data.error}`;
                            chatbotElement.innerHTML = `<p>${data.error}</p>`;
                        } else {
                            tokenCountElement.textContent = data.tokens;
                            resultElement.textContent = `Dự đoán: ${data.predicted_class}`;
                            resultElement.classList.add('result-loaded');
                            const llmResponse = data.llm_response;
                            const paragraphs = llmResponse.split('\n').filter(p => p.trim() !== '');
                            let formattedResponse = '';
                            paragraphs.forEach(paragraph => {
                                const formattedParagraph = paragraph.replace(/^(.*?):/g, '<strong>$1:</strong>');
                                formattedResponse += `<p>${formattedParagraph}</p>`;
                            });
                            chatbotElement.innerHTML = formattedResponse;
                            chatbotElement.classList.add('result-loaded');
                        }
                    } catch (error) {
                        resultElement.textContent = `Lỗi: ${error.message}`;
                        chatbotElement.innerHTML = '<p>Lỗi khi kết nối với server.</p>';
                    } finally {
                        predictBtn.disabled = false;
                        spinner.style.display = 'none';
                        setTimeout(() => {
                            resultElement.classList.remove('result-loaded');
                            chatbotElement.classList.remove('result-loaded');
                        }, 500);
                    }
                }

                // Cập nhật drag-and-drop để chỉ hoạt động ở chế độ upload
                function handleDragOver(e) {
                    if (currentMode === 'upload') {
                        e.preventDefault();
                        e.target.closest('.upload-area').classList.add('dragover');
                    }
                }

                function handleDragLeave(e) {
                    if (currentMode === 'upload') {
                        e.target.closest('.upload-area').classList.remove('dragover');
                    }
                }

                function handleDrop(e) {
                    if (currentMode === 'upload') {
                        e.preventDefault();
                        e.target.closest('.upload-area').classList.remove('dragover');
                        const file = e.dataTransfer.files[0];
                        if (file && file.type.startsWith('image/')) {
                            document.getElementById('imageInput').files = e.dataTransfer.files;
                            const preview = document.getElementById('previewImage');
                            preview.src = URL.createObjectURL(file);
                            preview.style.display = 'block';
                        } else {
                            alert('Vui lòng kéo thả file ảnh!');
                        }
                    }
                }
            </script>
</body>

</html>