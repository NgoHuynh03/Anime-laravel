@extends('layout')

@section('title', 'Trang Chủ - AnimeHay')

@section('content')

    @if(isset($sliderMovies) && $sliderMovies->count() > 0 && !isset($title))
    <div id="movieCarousel" class="carousel slide mb-5 shadow-lg" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach($sliderMovies as $index => $movie)
            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}" style="height: 450px;">
                <div style="position: absolute; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.4); z-index: 1;"></div>
                <img src="{{ $movie->thumb_url }}" class="d-block w-100 h-100" style="object-fit: cover; object-position: center 20%;" alt="{{ $movie->title }}">
                
                <div class="carousel-caption d-none d-md-block text-start" style="z-index: 2; bottom: 80px; left: 10%;">
                    <h1 class="fw-bold text-white" style="font-size: 3.5rem; text-shadow: 2px 2px 10px #000;">{{ $movie->title }}</h1>
                    <p class="fs-5 text-light" style="max-width: 600px; text-shadow: 1px 1px 5px #000;">{{ Str::limit($movie->description, 100) }}</p>
                    <a href="{{ route('movie.detail', $movie->slug) }}" class="btn btn-danger btn-lg rounded-pill px-4 shadow">
                        <i class="fas fa-play me-2"></i> Xem Ngay
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#movieCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon"></span></button>
        <button class="carousel-control-next" type="button" data-bs-target="#movieCarousel" data-bs-slide="next"><span class="carousel-control-next-icon"></span></button>
    </div>
    @endif

    <div class="container pb-5">
        
        <div class="d-flex align-items-center mb-4">
            <h3 class="fw-bold text-white border-start border-4 border-danger ps-3 mb-0">
                {{ isset($title) ? 'THỂ LOẠI: ' . strtoupper($title) : (request('search') ? 'TÌM KIẾM: ' . request('search') : 'PHIM MỚI CẬP NHẬT') }}
            </h3>
        </div>
        
        <div class="row row-cols-2 row-cols-md-4 row-cols-lg-5 g-4">
            @foreach($movies as $movie)
            <div class="col">
                <div class="movie-card h-100">
                    <a href="{{ route('movie.detail', $movie->slug) }}" class="d-block text-decoration-none">
                        
                        <div class="card-img-wrapper shadow">
                            <img src="{{ $movie->thumb_url }}" class="card-img-top" alt="{{ $movie->title }}">
                            
                            <div class="play-overlay"><i class="fas fa-play-circle"></i></div>
                            
                            <div class="status-badge">
                                @if($movie->episodes->count() > 0)
                                    @if($movie->episodes->count() == $movie->total_episodes)
                                        FULL {{ $movie->total_episodes }} Tập
                                    @else
                                        Tập {{ $movie->episodes->count() }}
                                    @endif
                                @else
                                    Trailer
                                @endif
                            </div>
                        </div>

                        <div class="movie-title" title="{{ $movie->title }}">
                            {{ $movie->title }}
                        </div>
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        @if($movies->count() == 0)
            <div class="text-center py-5">
                <i class="fas fa-ghost fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">Chưa có dữ liệu phim!</h4>
                <a href="{{ route('home') }}" class="btn btn-outline-secondary mt-3">Quay về trang chủ</a>
            </div>
        @endif

        <div class="mt-5 d-flex justify-content-center">
            {{ $movies->appends(request()->query())->links() }}
        </div>
    </div>

@endsection