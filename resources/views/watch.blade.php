@extends('layout')

@section('title', 'Xem phim ' . $movie->title . ' - ' . $currentEpisode->name)

@section('css')
<style>
    /* CSS TÙY CHỈNH CHO PLAYER */
    .video-wrapper {
        background: #000;
        width: 100%;
        /* Quan trọng: Thiết lập tỷ lệ 16:9 */
        aspect-ratio: 16/9;
        /* Quan trọng: Giới hạn chiều cao tối đa là 70% chiều cao màn hình hoặc 650px */
        /* Giúp player không bị dài ngoằng trên màn hình to */
        max-height: 70vh; 
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /* Ép iframe và video (Plyr) phải nằm gọn trong khung */
    .video-wrapper iframe,
    .video-wrapper video,
    .plyr {
        width: 100% !important;
        height: 100% !important;
        object-fit: contain; /* Đảm bảo video không bị méo */
    }

    /* CSS cho danh sách tập bên phải */
    .episode-list-card {
        background-color: rgba(33, 37, 41, 0.9); /* Màu tối mờ */
        height: 100%;
        max-height: 70vh; /* Cao bằng cái Player bên cạnh */
        display: flex;
        flex-direction: column;
    }
    
    .episode-scroll-area {
        overflow-y: auto;
        flex-grow: 1;
    }
</style>
@endsection

@section('content')

<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-muted text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('movie.detail', $movie->slug) }}" class="text-muted text-decoration-none">{{ $movie->title }}</a></li>
            <li class="breadcrumb-item active text-white" aria-current="page">{{ $currentEpisode->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-8 mb-4">
            
            {{-- LOGIC CHỌN SERVER --}}
            @php
                $currentServer = request('server_id') 
                    ? $currentEpisode->servers->where('id', request('server_id'))->first() 
                    : $currentEpisode->servers->first();
            @endphp

            <div class="video-wrapper">
                @if($currentServer)
                    {{-- 1. Nếu là Embed / Iframe --}}
                    @if($currentServer->type == 'embed' || Str::contains($currentServer->video_url, ['<iframe', 'player.']))
                        @if(Str::startsWith($currentServer->video_url, '<iframe'))
                            {!! $currentServer->video_url !!}
                        @else
                            <iframe src="{{ $currentServer->video_url }}" allowfullscreen scrolling="no" frameborder="0"></iframe>
                        @endif
                    
                    {{-- 2. Nếu là M3U8 / MP4 (Chạy Plyr) --}}
                    @else
                        <video id="player" playsinline controls data-poster="{{ $movie->thumb_url }}">
                            <source src="{{ $currentServer->video_url }}" 
                                    type="{{ Str::endsWith($currentServer->video_url, '.m3u8') ? 'application/x-mpegURL' : 'video/mp4' }}" />
                        </video>
                    @endif
                @else
                    <div class="text-center p-5">
                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                        <h5 class="text-muted">Chưa có Server nào cho tập này!</h5>
                    </div>
                @endif
            </div>

            <div class="mt-3 bg-dark bg-opacity-50 p-3 rounded border border-secondary border-opacity-25">
                <div class="d-flex align-items-center flex-wrap">
                    <span class="fw-bold text-danger me-3"><i class="fas fa-server"></i> SERVER:</span>
                    
                    @if($currentEpisode->servers->count() > 0)
                        @foreach($currentEpisode->servers as $server)
                            <a href="?server_id={{ $server->id }}" 
                               class="btn btn-sm me-2 mb-1 {{ (isset($currentServer) && $currentServer->id == $server->id) ? 'btn-danger' : 'btn-outline-secondary' }}">
                                {{ $server->server_name }}
                            </a>
                        @endforeach
                    @else
                        <span class="text-muted fst-italic">Đang cập nhật...</span>
                    @endif
                </div>
            </div>

            <div class="mt-4">
                <h3 class="fw-bold text-white">{{ $movie->title }}</h3>
                <h5 class="text-muted fw-normal">Đang xem: <span class="text-warning">{{ $currentEpisode->name }}</span></h5>
                <p class="text-secondary mt-3 p-3 bg-dark bg-opacity-25 rounded fst-italic">
                    <i class="fas fa-info-circle me-1"></i> Mẹo: Nếu Server này bị lag, hãy thử chuyển sang Server khác ở bên trên nhé!
                </p>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card episode-list-card border-secondary border-opacity-25 shadow">
                <div class="card-header bg-transparent border-secondary border-opacity-25">
                    <h5 class="mb-0 text-white"><i class="fas fa-list me-2 text-danger"></i> DANH SÁCH TẬP</h5>
                </div>
                <div class="card-body p-2 episode-scroll-area">
                    <div class="row g-2">
                        @foreach($episodes as $ep)
                            <div class="col-3 col-md-4 col-lg-3 px-1">
                                <a href="{{ route('movie.watch', ['slug' => $movie->slug, 'episode_slug' => $ep->slug]) }}" 
                                   class="btn btn-sm w-100 text-truncate {{ $currentEpisode->id == $ep->id ? 'btn-danger' : 'btn-dark border-secondary text-secondary' }}">
                                    {{ $ep->name }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="https://cdn.plyr.io/3.7.8/plyr.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/hls.js@1"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const video = document.querySelector('#player');
            if (video) {
                const source = video.getElementsByTagName('source')[0].src;
                const defaultOptions = {
                    controls: ['play-large', 'play', 'progress', 'current-time', 'mute', 'volume', 'settings', 'pip', 'fullscreen'],
                    settings: ['quality', 'speed'],
                    ratio: '16:9' // Ép tỷ lệ 16:9 cho Plyr
                };

                if (source.includes('.m3u8')) {
                    if (Hls.isSupported()) {
                        const hls = new Hls();
                        hls.loadSource(source);
                        hls.attachMedia(video);
                        hls.on(Hls.Events.MANIFEST_PARSED, function() {
                            new Plyr(video, defaultOptions);
                        });
                    } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                        video.src = source;
                        new Plyr(video, defaultOptions);
                    }
                } else {
                    new Plyr(video, defaultOptions);
                }
            }
        });
    </script>
@endsection