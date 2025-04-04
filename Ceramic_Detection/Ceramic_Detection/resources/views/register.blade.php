<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- Thêm Font Awesome để sử dụng icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(to bottom right, rgb(255, 255, 255), rgb(2, 247, 247));
            margin: 0;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .register-container {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
            animation: slideIn 0.8s ease-out;
        }

        @keyframes slideIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }

        h2 {
            color: #1a1a1a;
            margin-bottom: 10px;
            font-size: 28px;
        }

        form div {
            margin-bottom: 15px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #606770;
            font-weight: 500;
        }

        /* Style cho input với icon */
        .input-group {
            position: relative;
        }

        input {
            width: 100%;
            padding: 12px 12px 12px 40px; /* Thêm padding bên trái để chừa chỗ cho icon */
            border: 1px solid #dddfe2;
            border-radius: 6px;
            font-size: 16px;
            transition: all 0.3s ease-in-out;
            box-sizing: border-box;
        }

        input:focus {
            border-color: #1877f2;
            box-shadow: 0 0 5px rgba(24, 119, 242, 0.5);
            transform: scale(1.02);
        }

        .input-group i {
            position: absolute;
            left: 12px;
            top: 65%; /* Căn giữa icon theo chiều dọc so với input */
            transform: translateY(-50%);
            color: #606770;
            font-size: 16px;
        }

        /* Style cho nút Đăng ký với icon */
        button {
            width: 100%;
            padding: 12px;
            background: #1877f2;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 15px;
            transition: background 0.3s ease-in-out;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px; /* Khoảng cách giữa icon và chữ */
        }

        button:hover {
            background: #166fe5;
        }

        button i {
            font-size: 18px; /* Kích thước icon trong nút */
        }

        .alert-danger {
            color: #dc3545;
            background: #f8d7da;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            animation: fadeIn 0.5s ease-in-out;
        }

        .alert-danger ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .alert-danger li {
            margin-bottom: 5px;
        }

        .links {
            margin-top: 20px;
        }

        a {
            color: #1877f2;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        a:hover {
            color: #166fe5;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Đăng ký</h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="input-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                <i class="fas fa-user"></i>
            </div>
            <div class="input-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                <i class="fas fa-envelope"></i>
            </div>
            <div class="input-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <i class="fas fa-lock"></i>
            </div>
            <div class="input-group">
                <label for="password_confirmation">Confirm Password:</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
                <i class="fas fa-lock"></i>
            </div>
            <button type="submit">
                <i class="fas fa-user-plus"></i> Đăng ký
            </button>
        </form>

        <p class="links">Bạn đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập</a></p>
    </div>
</body>
</html>