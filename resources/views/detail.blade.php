@extends('layout')

@section('title', $movie->title . ' - AnimeHay')

@section('content')

<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-muted text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item active text-white" aria-current="page">{{ $movie->title }}</li>
        </ol>
    </nav>

    <div class="row mb-5">
        <div class="col-md-3">
            <div class="card border-0 shadow-lg" style="border-radius: 10px; overflow: hidden;">
                <img src="{{ $movie->thumb_url }}" class="img-fluid w-100" alt="{{ $movie->title }}" style="object-fit: cover;">
                
                @if($episodes->count() > 0)
                    <a href="{{ route('movie.watch', ['slug' => $movie->slug, 'episode_slug' => $episodes->first()->slug]) }}" 
                       class="btn btn-danger w-100 py-3 rounded-0 fw-bold fs-5">
                        <i class="fas fa-play me-2"></i> XEM NGAY
                    </a>
                @else
                    <button class="btn btn-secondary w-100 py-3 rounded-0" disabled>SẮP CHIẾU</button>
                @endif
            </div>
        </div>

        <div class="col-md-9 mt-4 mt-md-0">
            <h1 class="fw-bold text-white mb-3" style="font-size: 2.5rem;">{{ $movie->title }}</h1>
            
            <div class="mb-3">
                @foreach($movie->categories as $cat)
                    <a href="{{ route('category.movie', $cat->slug) }}" 
                       class="badge border border-secondary text-decoration-none text-light me-2 px-3 py-2">
                        {{ $cat->title }}
                    </a>
                @endforeach
            </div>

            <div class="bg-dark bg-opacity-50 p-3 rounded mb-4 border border-secondary border-opacity-25">
                <div class="row">
                    <div class="col-6 col-md-3 mb-2">
                        <span class="text-muted"><i class="fas fa-clock me-1"></i> Trạng thái:</span><br>
                        <span class="fw-bold text-danger">{{ $movie->status }}</span>
                    </div>
                    <div class="col-6 col-md-3 mb-2">
                        <span class="text-muted"><i class="fas fa-layer-group me-1"></i> Số tập:</span><br>
                        <span class="fw-bold text-white">{{ $episodes->count() }} / {{ $movie->total_episodes }}</span>
                    </div>
                    <div class="col-6 col-md-3 mb-2">
                        <span class="text-muted"><i class="fas fa-calendar me-1"></i> Năm phát hành:</span><br>
                        <span class="fw-bold text-white">{{ $movie->created_at->format('Y') }}</span>
                    </div>
                    <div class="col-6 col-md-3 mb-2">
                        <span class="text-muted"><i class="fas fa-eye me-1"></i> Lượt xem:</span><br>
                        <span class="fw-bold text-white">N/A</span>
                    </div>
                </div>
            </div>

            <h5 class="text-warning border-start border-4 border-danger ps-2 mb-2">NỘI DUNG PHIM</h5>
            <p class="text-light opacity-75" style="line-height: 1.6;">
                {{ $movie->description ?? 'Đang cập nhật nội dung...' }}
            </p>
        </div>
    </div>

    @if($episodes->count() > 0)
    <div class="mb-5">
        <h4 class="text-white border-bottom border-secondary pb-2 mb-4">
            <i class="fas fa-list-ol me-2 text-danger"></i> DANH SÁCH TẬP
        </h4>
        
        <div class="row row-cols-3 row-cols-md-6 row-cols-lg-8 g-2">
            @foreach($episodes as $ep)
                <div class="col">
                    <a href="{{ route('movie.watch', ['slug' => $movie->slug, 'episode_slug' => $ep->slug]) }}" 
                       class="btn btn-outline-secondary w-100 text-truncate">
                        {{ $ep->name }}
                    </a>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="mt-5">
        <h4 class="text-white border-bottom border-secondary pb-2 mb-3">
            <i class="fas fa-comments me-2 text-danger"></i> BÌNH LUẬN
        </h4>
        <div class="bg-dark bg-opacity-25 p-4 rounded text-center text-muted">
            Tính năng bình luận đang được phát triển...
        </div>
    </div>
</div>

@endsection