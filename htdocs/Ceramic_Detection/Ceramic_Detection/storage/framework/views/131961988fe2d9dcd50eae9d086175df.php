<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đổi Mật Khẩu</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
    <?php if($recaptchaEnabled): ?>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <?php endif; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* Tái sử dụng style từ login.blade.php */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 rgba(24, 119, 242, 0.5); }
            50% { box-shadow: 0 0 15px rgba(24, 119, 242, 0.5); }
        }
        body {
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(to bottom right, #4facfe, rgb(255, 255, 255));
            margin: 0;
        }
        .change-password-container {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
            width: 400px;
            text-align: center;
            animation: fadeIn 0.8s ease-in-out;
        }
        h2 { color: #1a1a1a; margin-bottom: 25px; font-size: 28px; font-weight: bold; }
        .input-group { position: relative; margin-bottom: 15px; text-align: left; }
        label { display: block; margin-bottom: 5px; color: #606770; font-weight: 500; }
        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px 12px 12px 40px;
            border: 1px solid #dddfe2;
            border-radius: 6px;
            font-size: 16px;
            box-sizing: border-box;
            transition: all 0.3s;
        }
        input[type="email"]:focus, input[type="password"]:focus {
            border-color: #1877f2;
            box-shadow: 0 0 8px rgba(24, 119, 242, 0.5);
            outline: none;
        }
        .input-group i {
            position: absolute;
            left: 12px;
            top: 65%;
            transform: translateY(-50%);
            color: #606770;
            font-size: 16px;
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
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        button:hover { background: #166fe5; animation: pulse 1s infinite; }
        button i { font-size: 18px; }
        .alert-danger { color: #dc3545; background: #f8d7da; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .divider { border-top: 1px solid #dddfe2; margin: 20px 0; animation: fadeIn 1s ease-in-out; }
    </style>
</head>
<body>
    <div class="change-password-container">
        <h2>ĐỔI MẬT KHẨU</h2>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if(session('success')): ?>
            <div class="alert alert-success">
                <?php echo e(session('success')); ?>

            </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('password.change')); ?>">
            <?php echo csrf_field(); ?>
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo e(old('email')); ?>" required>
                <i class="fas fa-envelope"></i>
            </div>

            <div class="input-group">
                <label for="current_password">Mật khẩu cũ</label>
                <input type="password" id="current_password" name="current_password" required>
                <i class="fas fa-lock"></i>
            </div>

            <div class="input-group">
                <label for="new_password">Mật khẩu mới</label>
                <input type="password" id="new_password" name="new_password" required>
                <i class="fas fa-lock"></i>
            </div>

            <div class="input-group">
                <label for="new_password_confirmation">Xác nhận mật khẩu mới</label>
                <input type="password" id="new_password_confirmation" name="new_password_confirmation" required>
                <i class="fas fa-lock"></i>
            </div>

            <?php if($recaptchaEnabled): ?>
                <div class="g-recaptcha" data-sitekey="<?php echo e(env('RECAPTCHA_SITE_KEY')); ?>"></div>
                <?php $__errorArgs = ['g-recaptcha-response'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="alert alert-danger"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <?php endif; ?>

            <button type="submit">
                <i class="fas fa-key"></i> ĐỔI MẬT KHẨU
            </button>
        </form>

        <div class="divider"></div>
        <p><a href="<?php echo e(route('login')); ?>">Quay lại đăng nhập</a></p>
    </div>
</body>
</html><?php /**PATH D:\Xampp\htdocs\Ceramic_Detection\Ceramic_Detection\resources\views/change-password.blade.php ENDPATH**/ ?>