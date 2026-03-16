<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Thể Loại</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Thêm Thể Loại Mới</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.category.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Tên Thể Loại</label>
                                <input type="text" name="title" class="form-control" placeholder="Ví dụ: Isekai" required>
                            </div>
                            <button type="submit" class="btn btn-success w-100">Lưu Thể Loại</button>
                            <a href="{{ route('admin.category.index') }}" class="btn btn-secondary w-100 mt-2">Hủy bỏ</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>