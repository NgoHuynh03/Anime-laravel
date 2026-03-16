<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản Lý Phim - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .action-col { width: 170px; }
        .movie-thumb { width: 50px; height: 70px; object-fit: cover; border-radius: 4px; }
        tr { vertical-align: middle; }
    </style>
</head>
<body class="bg-light">

    <div class="container mt-5">
        
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
            <h2 class="text-primary fw-bold">Danh Sách Phim</h2>
            <div>
                <span class="me-3 fw-bold text-secondary">
                    Xin chào, {{ Auth::user()->name }}
                </span>
                <a href="{{ route('logout') }}" class="btn btn-outline-danger me-2">Đăng xuất</a>
                <a href="{{ route('admin.category.index') }}" class="btn btn-outline-primary me-2">Quản lý Thể loại</a>
                <a href="{{ route('admin.movie.create') }}" class="btn btn-primary">+ Thêm Phim Mới</a>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body py-3">
                <form action="{{ route('admin.movie.index') }}" method="GET" class="row g-2 align-items-center">
                    <div class="col-auto">
                        <label class="fw-bold text-secondary"><i class="fas fa-search"></i> Tìm kiếm:</label>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="search" class="form-control" placeholder="Nhập tên phim cần tìm..." value="{{ request('search') }}">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-info text-white fw-bold">Tìm ngay</button>
                        @if(request('search'))
                            <a href="{{ route('admin.movie.index') }}" class="btn btn-outline-secondary">Xóa lọc</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <strong>Thành công!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow border-0">
            <div class="card-body p-0">
                <table class="table table-striped table-hover mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col" class="ps-3">#ID</th>
                            <th scope="col">Poster</th>
                            <th scope="col">Thông Tin Phim</th>
                            <th scope="col">Tiến độ</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col" class="text-end pe-4">Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($movies as $movie)
                        <tr>
                            <td class="ps-3 fw-bold text-secondary">{{ $movie->id }}</td>
                            <td>
                                <img src="{{ $movie->thumb_url }}" class="movie-thumb shadow-sm" alt="poster">
                            </td>
                            <td>
                                <span class="fw-bold text-primary">{{ $movie->title }}</span>
                                <br>
                                <small class="text-muted fst-italic">Slug: {{ $movie->slug }}</small>
                            </td>
                            <td>
                                <span class="badge bg-info text-dark border border-info">
                                    {{ $movie->episodes->count() }} / {{ $movie->total_episodes }} Tập
                                </span>
                            </td>
                            <td>
                                @if($movie->status == 'Hoàn thành')
                                    <span class="badge bg-success">Hoàn thành</span>
                                @elseif($movie->status == 'Trailer')
                                    <span class="badge bg-secondary">Trailer</span>
                                @else
                                    <span class="badge bg-warning text-dark">Đang chiếu</span>
                                @endif
                            </td>
                            <td class="text-end pe-4 action-col">
                                <div class="d-flex flex-column gap-1">
                                    <a href="{{ route('admin.episode.create', $movie->id) }}" class="btn btn-sm btn-success">
                                        <i class="fas fa-plus-circle"></i> Thêm Tập
                                    </a>
                                    <a href="{{ route('admin.episode.index', $movie->id) }}" class="btn btn-sm btn-info text-white">
                                        <i class="fas fa-list"></i> DS Tập ({{ $movie->episodes->count() }})
                                    </a>
                                    <a href="{{ route('admin.movie.edit', $movie->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Sửa phim
                                    </a>
                                    <form action="{{ route('admin.movie.destroy', $movie->id) }}" method="POST" onsubmit="return confirm('CẢNH BÁO: Xóa phim này sẽ xóa toàn bộ tập và server!');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger w-100">
                                            <i class="fas fa-trash"></i> Xóa phim
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach

                        @if($movies->isEmpty())
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <h5 class="mb-3">Không tìm thấy phim nào!</h5>
                                @if(request('search'))
                                    <a href="{{ route('admin.movie.index') }}" class="btn btn-secondary">Quay lại danh sách đầy đủ</a>
                                @else
                                    <a href="{{ route('admin.movie.create') }}" class="btn btn-primary">Thêm phim ngay</a>
                                @endif
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="mt-4 d-flex justify-content-center">
            {{ $movies->appends(request()->query())->links() }}
        </div>
        
        <div class="text-center mt-3 mb-5">
            <a href="{{ route('home') }}" class="text-decoration-none text-secondary">
                &larr; Quay về Trang Chủ Website
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>