<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Tập Mới - {{ $movie->title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">

    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-9">
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4>Thêm tập cho phim: <span class="text-primary">{{ $movie->title }}</span></h4>
                    <a href="{{ route('admin.episode.index', $movie->id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Quay lại DS Tập
                    </a>
                </div>
                
                <div class="card shadow border-0">
                    <div class="card-header bg-success text-white">
                        <ul class="nav nav-tabs card-header-tabs border-0" id="myTab" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active text-dark fw-bold bg-white" id="single-tab" data-bs-toggle="tab" data-bs-target="#single" type="button">
                                    <i class="fas fa-plus"></i> THÊM LẺ (1 Tập)
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link text-white fw-bold" id="bulk-tab" data-bs-toggle="tab" data-bs-target="#bulk" type="button" style="background: transparent; opacity: 0.8;">
                                    <i class="fas fa-layer-group"></i> THÊM NHANH (Nhiều Tập)
                                </button>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="card-body p-4">
                        
                        <div class="tab-content" id="myTabContent">
                            
                            <div class="tab-pane fade show active" id="single">
                                <form action="{{ route('admin.episode.store', $movie->id) }}" method="POST">
                                    @csrf
                                    
                                    <div class="row">
                                        <div class="col-md-5 mb-3">
                                            <label class="form-label fw-bold">Tên Tập</label>
                                            <input type="text" name="name" class="form-control" placeholder="Ví dụ: Tập 01" required>
                                        </div>

                                        <div class="col-md-7 mb-3">
                                            <label class="form-label fw-bold text-success">Server (Nguồn phát)</label>
                                            <div class="card bg-light border p-2">
                                                <div class="input-group mb-2">
                                                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-history text-muted"></i></span>
                                                    <select class="form-select border-start-0" id="quick_select_single" onchange="fillServerName(this.value, 'server_name_single')">
                                                        <option value="" selected disabled>-- Chọn server cũ --</option>
                                                        @foreach($existingServers as $svName)
                                                            <option value="{{ $svName }}">{{ $svName }}</option>
                                                        @endforeach
                                                        <option value="Server VIP">Server VIP (Mặc định)</option>
                                                    </select>
                                                </div>
                                                
                                                <div class="input-group">
                                                    <span class="input-group-text bg-warning text-dark fw-bold">Tên:</span>
                                                    <input type="text" name="server_name" id="server_name_single" class="form-control fw-bold text-primary" placeholder="Nhập tên server mới vào đây..." required>
                                                </div>
                                                <div class="form-text text-muted" style="font-size: 12px;">
                                                    <i class="fas fa-info-circle"></i> Muốn tạo Server mới? Cứ nhập tên mới vào ô bên dưới là được.
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label fw-bold">Loại Link</label>
                                            <select name="type" class="form-select">
                                                <option value="embed">Embed (Iframe)</option>
                                                <option value="m3u8">M3U8 / MP4 (Direct)</option>
                                            </select>
                                        </div>

                                        <div class="col-md-8 mb-3">
                                            <label class="form-label fw-bold">Link Video / Iframe</label>
                                            <input type="text" name="video_url" class="form-control" placeholder="https://..." required>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-success w-100 fw-bold py-2">
                                        <i class="fas fa-save me-2"></i> LƯU TẬP PHIM
                                    </button>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="bulk">
                                <form action="{{ route('admin.episode.store', $movie->id) }}" method="POST">
                                    @csrf
                                    
                                    <div class="row mb-3 bg-light p-3 rounded border">
                                        <div class="col-md-12 mb-2 fw-bold text-danger text-uppercase">Cấu hình chung cho toàn bộ danh sách:</div>
                                        
                                        <div class="col-md-6">
                                            <label class="form-label">Tên Server áp dụng</label>
                                            
                                            <select class="form-select mb-2 form-select-sm" onchange="fillServerName(this.value, 'server_name_bulk')">
                                                <option value="" selected disabled>-- Chọn server cũ --</option>
                                                @foreach($existingServers as $svName)
                                                    <option value="{{ $svName }}">{{ $svName }}</option>
                                                @endforeach
                                                <option value="Server VIP">Server VIP</option>
                                            </select>

                                            <input type="text" name="server_name" id="server_name_bulk" class="form-control fw-bold" placeholder="Hoặc nhập tên Server mới..." required>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Loại Link</label>
                                            <select name="bulk_type" class="form-select border-danger">
                                                <option value="embed">Embed (Iframe)</option>
                                                <option value="m3u8">M3U8 / MP4 (Direct)</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Dán danh sách tập vào đây:</label>
                                        <div class="form-text text-muted mb-2">
                                            Định dạng: <code>Tên Tập|Link Video</code><br>
                                        </div>
                                        <textarea name="bulk_episodes" class="form-control" rows="10" placeholder="Tập 01|https://..."></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-danger w-100 fw-bold py-2">
                                        <i class="fas fa-bolt me-2"></i> XỬ LÝ & LƯU HÀNG LOẠT
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Hàm này đơn giản là copy text từ menu chọn xuống ô nhập liệu
        function fillServerName(value, targetId) {
            if(value) {
                document.getElementById(targetId).value = value;
            }
        }
    </script>
</body>
</html>