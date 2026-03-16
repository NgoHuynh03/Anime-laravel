<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #1a1a2e; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card { width: 400px; border: none; }
    </style>
</head>
<body>

    <div class="card shadow p-4">
        <h3 class="text-center mb-4">Đăng Nhập</h3>
        
        @if($errors->any())
            <div class="alert alert-danger text-center">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" placeholder="admin@gmail.com" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Mật khẩu</label>
                <input type="password" name="password" class="form-control" placeholder="123456" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Vào trang quản trị</button>
        </form>
        
        <div class="text-center mt-3">
            <a href="{{ route('home') }}" class="text-decoration-none">Về trang chủ</a>
        </div>
    </div>

</body>
</html>