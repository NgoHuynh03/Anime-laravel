<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Tập: {{ $episode->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">

    <div class="container mt-5 mb-5">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-0">
                    Sửa tập phim: <span class="text-primary fw-bold">{{ $episode->movie->title }}</span>
                </h4>
                <p class="text-muted mb-0">Đang chọn: <strong>{{ $episode->name }}</strong></p>
            </div>
            <a href="{{ route('admin.episode.index', $episode->movie_id) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i> Quay lại DS Tập
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success shadow-sm">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif

        <div class="row">
            
            <div class="col-md-4 mb-4">
                <div class="card shadow border-0 h-100">
                    <div class="card-header bg-warning text-dark fw-bold">
                        <i class="fas fa-edit me-2"></i> THÔNG TIN TẬP
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.episode.update', $episode->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tên tập phim</label>
                                <input type="text" name="name" class="form-control" value="{{ $episode->name }}" placeholder="Ví dụ: Tập 01">
                                <small class="text-muted">Slug sẽ tự động cập nhật theo tên.</small>
                            </div>
                            <button class="btn btn-warning w-100 fw-bold">
                                <i class="fas fa-save me-2"></i> LƯU TÊN TẬP
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8 mb-4">
                <div class="card shadow border-0 h-100">
                    <div class="card-header bg-primary text-white fw-bold d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-server me-2"></i> QUẢN LÝ LINK (SERVER)</span>
                        <span class="badge bg-light text-primary">{{ $episode->servers->count() }} Link</span>
                    </div>
                    
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0 align-middle">
                                <thead class="table-light text-secondary">
                                    <tr>
                                        <th class="ps-3">Tên Server</th>
                                        <th>Loại</th>
                                        <th>Link Video</th>
                                        <th class="text-end pe-3">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($episode->servers as $server)
                                    <tr>
                                        <td class="ps-3 fw-bold text-primary">{{ $server->server_name }}</td>
                                        <td>
                                            @if($server->type == 'embed')
                                                <span class="badge bg-info text-dark">Embed</span>
                                            @else
                                                <span class="badge bg-success">M3U8 / MP4</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="text-truncate text-muted" style="max-width: 250px;" title="{{ $server->video_url }}">
                                                {{ $server->video_url }}
                                            </div>
                                        </td>
                                        <td class="text-end pe-3">
                                            <a href="{{ route('admin.server.destroy', $server->id) }}" 
                                               class="btn btn-sm btn-outline-danger"
                                               onclick="return confirm('CẢNH BÁO: Bạn chắc chắn muốn xóa Server {{ $server->server_name }}?');">
                                                <i class="fas fa-trash-alt"></i> Xóa
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach

                                    @if($episode->servers->isEmpty())
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">
                                                <i class="fas fa-exclamation-triangle mb-2"></i><br>
                                                Chưa có link nào. Hãy thêm link mới bên dưới!
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer bg-light p-3 border-top">
                        <h6 class="fw-bold text-success mb-3">
                            <i class="fas fa-plus-circle me-2"></i> Thêm Server Mới (Dự phòng)
                        </h6>
                        
                        <form action="{{ route('admin.server.store', $episode->id) }}" method="POST">
                            @csrf
                            <div class="row g-2 align-items-end">
                                <div class="col-md-3">
                                    <label class="form-label small text-muted mb-1">Tên Server</label>
                                    <input type="text" name="server_name" class="form-control" placeholder="VD: Server VIP 2" required>
                                </div>
                                
                                <div class="col-md-3">
                                    <label class="form-label small text-muted mb-1">Loại Link</label>
                                    <select name="type" class="form-select">
                                        <option value="embed">Embed (Iframe)</option>
                                        <option value="m3u8">M3U8 / MP4</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-4">
                                    <label class="form-label small text-muted mb-1">Link Video / Iframe</label>
                                    <input type="text" name="video_url" class="form-control" placeholder="https://..." required>
                                </div>
                                
                                <div class="col-md-2">
                                    <button class="btn btn-success w-100 fw-bold">
                                        <i class="fas fa-plus"></i> Thêm
                                    </button>
                                </div>
                            </div>
                            <div class="form-text mt-2">
                                <i class="fas fa-info-circle"></i> Nếu link là <code>embed</code>, hệ thống sẽ tự động bọc thẻ iframe nếu bạn quên.
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>