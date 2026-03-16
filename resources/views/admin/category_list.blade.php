<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Thể loại</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Danh Sách Thể Loại</h4>
            <div>
                <a href="{{ route('admin.movie.index') }}" class="btn btn-secondary me-2">Quay lại DS Phim</a>
                <a href="{{ route('admin.category.create') }}" class="btn btn-success">+ Thêm Thể Loại</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow">
            <div class="card-body p-0">
                <table class="table table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Tên Thể Loại</th>
                            <th>Slug (Đường dẫn)</th>
                            <th class="text-end pe-4">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $cat)
                        <tr>
                            <td>{{ $cat->id }}</td>
                            <td class="fw-bold">{{ $cat->title }}</td>
                            <td>{{ $cat->slug }}</td>
                            <td class="text-end pe-4">
                                <form action="{{ route('admin.category.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn xóa?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Xóa</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>