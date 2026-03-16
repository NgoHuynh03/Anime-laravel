<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AnimeHay - Web Phim Hoạt Hình')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />

    <style>
        /* --- BẢNG MÀU CHUẨN ANIME --- */
        :root {
            --bg-body: #0f172a;       /* Nền chính (Xanh đen rất tối) */
            --bg-header: #1e293b;     /* Nền Header/Footer/Card */
            --text-main: #e2e8f0;     /* Chữ chính (Trắng đục) */
            --text-muted: #94a3b8;    /* Chữ phụ (Xám xanh) */
            --accent-color: #ef4444;  /* Màu nhấn (Đỏ tươi - giống Netflix) */
            --accent-hover: #dc2626;  /* Màu nhấn khi hover */
        }

        body { 
            background-color: var(--bg-body); 
            color: var(--text-main); 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh; 
            display: flex; 
            flex-direction: column; 
        }

        /* HEADER & NAVBAR */
        .navbar { background-color: var(--bg-header); border-bottom: 1px solid #334155; }
        .navbar-brand { font-weight: 800; color: var(--accent-color) !important; letter-spacing: 1px; }
        .nav-link { color: var(--text-main) !important; font-weight: 500; transition: 0.2s; }
        .nav-link:hover { color: var(--accent-color) !important; }
        
        .dropdown-menu { background-color: var(--bg-header); border: 1px solid #334155; }
        .dropdown-item { color: var(--text-main); }
        .dropdown-item:hover { background-color: #334155; color: var(--accent-color); }

        /* SEARCH BAR */
        .search-input { background-color: #0f172a; border: 1px solid #334155; color: #fff; border-radius: 20px 0 0 20px; }
        .search-input:focus { background-color: #0f172a; color: #fff; border-color: var(--accent-color); box-shadow: none; }
        .search-btn { border-radius: 0 20px 20px 0; background-color: var(--accent-color); border: none; color: white; }
        .search-btn:hover { background-color: var(--accent-hover); }

        /* FOOTER */
        footer { margin-top: auto; background-color: var(--bg-header); border-top: 1px solid #334155; padding: 30px 0; }
        
        /* CÁC CSS CHUNG KHÁC */
        a { text-decoration: none; }
        
        /* CSS CHO CARD PHIM (Sẽ dùng ở trang Home) */
        .movie-card { 
            background: transparent; 
            border: none; 
            transition: transform 0.3s ease; 
        }
        .movie-card:hover { transform: translateY(-5px); }
        
        .card-img-wrapper { 
            position: relative; 
            border-radius: 8px; 
            overflow: hidden; 
            aspect-ratio: 2/3; /* Tỷ lệ ảnh dọc chuẩn poster */
        }
        .card-img-top { 
            width: 100%; 
            height: 100%; 
            object-fit: cover; 
            transition: 0.3s; 
        }
        .movie-card:hover .card-img-top { filter: brightness(0.7); transform: scale(1.05); }
        
        /* Nút Play khi hover */
        .play-overlay {
            position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
            font-size: 3rem; color: #fff; opacity: 0; transition: 0.3s; z-index: 2;
        }
        .movie-card:hover .play-overlay { opacity: 1; }

        /* Badge Trạng thái tập (Góc trên phải) */
        .status-badge {
            position: absolute; top: 10px; right: 10px;
            background: rgba(0, 0, 0, 0.7); color: #fff;
            padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: bold;
            backdrop-filter: blur(2px); border: 1px solid rgba(255,255,255,0.2);
        }

        /* Tiêu đề phim bên dưới */
        .movie-title {
            margin-top: 10px;
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-main);
            text-align: center;
            /* Cắt chữ nếu quá dài (1 dòng) */
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            transition: 0.2s;
        }
        .movie-card:hover .movie-title { color: var(--accent-color); }

        /* Pagination đẹp */
        .pagination .page-link { background: var(--bg-header); border-color: #334155; color: var(--text-main); }
        .pagination .active .page-link { background: var(--accent-color); border-color: var(--accent-color); color: #fff; }
    </style>
    
    @yield('css') </head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-play-circle me-1"></i> ANIME<span style="color: #fff">HAY</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active text-danger' : '' }}" href="{{ route('home') }}">Trang chủ</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            Thể loại
                        </a>
                        <ul class="dropdown-menu shadow">
                            @if(isset($global_categories))
                                @foreach($global_categories as $cat)
                                    <li><a class="dropdown-item" href="{{ route('category.movie', $cat->slug) }}">{{ $cat->title }}</a></li>
                                @endforeach
                            @endif
                        </ul>
                    </li>
                </ul>

                <form class="d-flex" action="{{ route('home') }}" method="GET">
                    <div class="input-group">
                        <input class="form-control search-input" type="search" name="search" 
                               placeholder="Tìm anime..." value="{{ request('search') }}">
                        <button class="btn search-btn" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </form>

                @auth
                    <a href="{{ route('admin.movie.index') }}" class="btn btn-outline-light ms-3 btn-sm rounded-pill px-3">
                        <i class="fas fa-user-cog"></i> Admin
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer>
        <div class="container text-center">
            <h5 class="fw-bold text-white mb-3">ANIMEHAY</h5>
            <div class="mb-3">
                <a href="#" class="text-muted mx-2 fs-5"><i class="fab fa-facebook"></i></a>
                <a href="#" class="text-muted mx-2 fs-5"><i class="fab fa-discord"></i></a>
                <a href="#" class="text-muted mx-2 fs-5"><i class="fab fa-tiktok"></i></a>
            </div>
            <p class="mb-0 text-muted small">
                © 2025 AnimeHay. Nơi thỏa mãn đam mê Anime của bạn.<br>
                Code with <i class="fas fa-heart text-danger"></i> by Laravel.
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @yield('scripts') </body>
</html>