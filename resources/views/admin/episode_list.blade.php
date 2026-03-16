<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh Sách Tập - {{ $movie->title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
            <div>
                <h4 class="mb-1">Quản lý tập phim: <span class="text-primary">{{ $movie->title }}</span></h4>
                <small class="text-muted">Tổng số: {{ $episodes->count() }} tập đã lên sóng</small>
            </div>
            <div>
                <a href="{{ route('admin.movie.index') }}" class="btn btn-secondary me-2">
                    <i class="fas fa-arrow-left"></i> Quay lại DS Phim
                </a>
                <a href="{{ route('admin.episode.create', $movie->id) }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Thêm Tập Mới
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card shadow border-0">
            <div class="card-body p-0">
                <table class="table table-striped table-hover mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">Tên Tập</th>
                            <th>Slug</th>
                            <th>Servers (Link)</th>
                            <th>Ngày đăng</th>
                            <th class="text-end pe-4">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($episodes as $ep)
                        <tr>
                            <td class="ps-4 fw-bold">{{ $ep->name }}</td>
                            
                            <td class="text-secondary fst-italic">{{ $ep->slug }}</td>
                            
                            <td>
                                @if($ep->servers->count() > 0)
                                    <span class="badge bg-primary">
                                        {{ $ep->servers->count() }} Servers
                                    </span>
                                    <br>
                                    <small class="text-muted" style="font-size: 11px;">
                                        (Mặc định: {{ $ep->servers->first()->server_name }})
                                    </small>
                                @else
                                    <span class="badge bg-danger">Chưa có link!</span>
                                @endif
                            </td>

                            <td>{{ $ep->created_at->format('d/m/Y H:i') }}</td>
                            
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.episode.edit', $ep->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Sửa / Thêm Link
                                </a>

                                <form action="{{ route('admin.episode.destroy', $ep->id) }}" method="POST" class="d-inline" onsubmit="return confirm('CẢNH BÁO: Xóa tập này sẽ xóa luôn tất cả các Link Server bên trong.\nBạn có chắc chắn không?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i> Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach

                        @if($episodes->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fas fa-film fa-3x mb-3"></i>
                                    <h5>Chưa có tập phim nào.</h5>
                                    <a href="{{ route('admin.episode.create', $movie->id) }}" class="btn btn-primary mt-2">Thêm tập ngay</a>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="text-center mt-4 mb-5 text-muted">
            <small>&copy; AnimeHay Admin Panel</small>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>