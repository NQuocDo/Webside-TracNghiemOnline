<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lỗi 503 - Dịch vụ tạm thời không khả dụng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .error-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .error-card {
            background: white;
            border-radius: 8px;
            padding: 3rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 500px;
            width: 100%;
        }

        .error-code {
            font-size: 4rem;
            font-weight: bold;
            color: #dc3545;
            margin-bottom: 1rem;
        }

        .error-title {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 1rem;
        }

        .error-description {
            color: #666;
            margin-bottom: 2rem;
            line-height: 1.5;
        }

        .btn-home {
            background-color: #007bff;
            color: white;
            padding: 10px 25px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
        }

        .btn-home:hover {
            background-color: #0056b3;
            color: white;
        }
    </style>
</head>

<body>
    <div class="error-container">
        <div class="error-card">
            <div class="error-code">503</div>
            <h1 class="error-title">Dịch vụ tạm thời không khả dụng</h1>
            <p class="error-description">
                Chúng tôi đang gặp sự cố hệ thống. Vui lòng quay lại sau.
            </p>

            @php
                $user = Auth::user();
            @endphp

            @if($user && $user->vai_tro === 'truong_khoa')
                <a href="{{ route('dean.dashboard') }}" class="btn-home">Về trang quản trị</a>
            @elseif($user && $user->vai_tro === 'giang_vien')
                <a href="{{ route('lecturer.dashboard') }}" class="btn-home">Về trang giảng viên</a>
            @elseif($user && $user->vai_tro === 'sinh_vien')
                <a href="{{ route('student.dashboard') }}" class="btn-home">Về trang sinh viên</a>
            @else
                <a href="/" class="btn-home">Về trang chủ</a>
            @endif
        </div>
    </div>
</body>

</html>