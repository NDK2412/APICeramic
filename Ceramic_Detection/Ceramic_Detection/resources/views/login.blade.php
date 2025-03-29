<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0%, 100% {
                box-shadow: 0 0 0 rgba(24, 119, 242, 0.5);
            }
            50% {
                box-shadow: 0 0 15px rgba(24, 119, 242, 0.5);
            }
        }

        body {
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(to bottom right, #4facfe,rgb(255, 255, 255));
            margin: 0;
        }

        .login-container {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            width: 400px;
            text-align: center;
            animation: fadeIn 0.8s ease-in-out;
        }

        h2 {
            color: #1a1a1a;
            margin-bottom: 10px;
            font-size: 28px;
            font-weight: bold;
        }

        .welcome-text {
            color: #606770;
            margin-bottom: 25px;
            line-height: 1.5;
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

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #dddfe2;
            border-radius: 6px;
            font-size: 16px;
            box-sizing: border-box;
            transition: all 0.3s;
        }

        input:focus {
            border-color: #1877f2;
            box-shadow: 0 0 8px rgba(24, 119, 242, 0.5);
            outline: none;
        }

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
            transition: all 0.3s;
        }

        button:hover {
            background: #166fe5;
            animation: pulse 1s infinite;
        }

        .links {
            margin-top: 20px;
            text-align: center;
        }

        a {
            color: #1877f2;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        a:hover {
            text-decoration: underline;
            color: #145dbf;
        }

        .divider {
            border-top: 1px solid #dddfe2;
            margin: 20px 0;
            animation: fadeIn 1s ease-in-out;
        }

        .alert-danger {
            color: #dc3545;
            background: #f8d7da;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            animation: fadeIn 0.5s ease-in-out;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>SIGN IN</h2>
        <div class="welcome-text">
            <h3>Hello, Friend!</h3>
            <p>Register with your personal details to use all of site features</p>
        </div>

        <div class="divider"></div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            </div>
            
            <div>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="links">
                <a href="#forgot-password">Forget Your Password?</a>
            </div>

            <button type="submit">SIGN IN</button>
        </form>

        <p style="margin-top: 20px; color: #606770;">Don't have an account? <a href="{{ route('register') }}">Register here</a></p>
    </div>
</body>
</html>