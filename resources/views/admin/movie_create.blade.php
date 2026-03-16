<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Phim Mới</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                
                <div class="card shadow border-0">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Thêm Phim Mới</h4>
                    </div>
                    <div class="card-body p-4">

                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('admin.movie.store') }}" method="POST">
                            @csrf <div class="mb-3">
                                <label class="form-label fw-bold">Tên phim</label>
                                <input type="text" name="title" class="form-control" placeholder="Ví dụ: Doraemon" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Link Ảnh Poster (Thumb URL)</label>
                                <input type="url" name="thumb_url" class="form-control" placeholder="https://..." required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Tổng số tập</label>
                                    <input type="number" name="total_episodes" class="form-control" value="12">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Trạng thái</label>
                                    <select name="status" class="form-select">
                                        <option value="Đang tiến hành">Đang tiến hành</option>
                                        <option value="Hoàn thành">Hoàn thành</option>
                                        <option value="Trailer">Trailer</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3 p-3 bg-light border rounded">
                                <label class="form-label fw-bold text-primary">Chọn Thể Loại:</label>
                                <div class="row">
                                    @foreach($categories as $cat)
                                    <div class="col-6 col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $cat->id }}" id="cat_{{ $cat->id }}">
                                            <label class="form-check-label" for="cat_{{ $cat->id }}">
                                                {{ $cat->title }}
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Mô tả phim</label>
                                <textarea name="description" class="form-control" rows="4"></textarea>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">Lưu Phim</button>
                                <a href="{{ route('admin.movie.index') }}" class="btn btn-secondary">Hủy bỏ / Quay lại</a>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>