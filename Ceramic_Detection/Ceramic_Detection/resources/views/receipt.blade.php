<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Hóa Đơn Nạp Tiền</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
        }
        h1 {
            color: #1e88e5;
            text-align: center;
        }
        p {
            font-size: 16px;
            line-height: 1.5;
        }
        img {
            max-width: 300px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Hóa Đơn Nạp Tiền</h1>
        <p><strong>Số tiền:</strong> {{ number_format($record->amount) }} VNĐ</p>
        <p><strong>Tokens nhận:</strong> {{ $record->tokens_added }}</p>
        <p><strong>Ngày duyệt:</strong> {{ $record->approved_at }}</p>
        @if ($proof_image && file_exists($proof_image))
            <p><strong>Ảnh chứng minh:</strong></p>
            <img src="{{ $proof_image }}" alt="Ảnh chứng minh">
        @else
            <p><strong>Ảnh chứng minh:</strong> Không có</p>
        @endif
    </div>
</body>
</html>