<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Category;
use Illuminate\Support\Str;

class MovieController extends Controller
{
    // 1. Trang chủ (Có phân trang 30 phim/page)
    public function index(Request $request)
    {
        $search = $request->input('search');

        if ($search) {
            // Nếu có tìm kiếm -> Phân trang 30 kết quả
            $movies = Movie::where('title', 'LIKE', "%{$search}%")
                           ->orderBy('updated_at', 'desc')
                           ->paginate(30); // <--- SỬA THÀNH 30
            
            // Khi tìm kiếm thì không hiện Slider
            $sliderMovies = collect(); 
        } else {
            // Lấy danh sách phim bình thường -> Phân trang 30
            $movies = Movie::orderBy('updated_at', 'desc')
                           ->paginate(30); // <--- SỬA THÀNH 30
            
            // Slider lấy 5 phim mới cập nhật (updated_at)
            $sliderMovies = Movie::orderBy('updated_at', 'desc')->take(5)->get();
        }
        
        return view('home', compact('movies', 'search', 'sliderMovies'));
    }

    // ... (Giữ nguyên show và watch) ...
    public function show($slug)
    {
        $movie = Movie::where('slug', $slug)->firstOrFail();
        $episodes = $movie->episodes()->orderBy('id', 'asc')->get();
        return view('detail', compact('movie', 'episodes'));
    }

    public function watch($slug, $episode_slug)
    {
        $movie = Movie::where('slug', $slug)->firstOrFail();
        $currentEpisode = $movie->episodes()->where('slug', $episode_slug)->firstOrFail();
        $episodes = $movie->episodes()->orderBy('id', 'asc')->get();
        return view('watch', compact('movie', 'currentEpisode', 'episodes'));
    }

    // 4. Lọc phim theo thể loại (Cũng phân trang 30)
    public function listByCategory($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        
        // Sửa paginate(20) thành paginate(30)
        $movies = $category->movies()->orderBy('created_at', 'desc')->paginate(30); 

        return view('home', compact('movies'))->with('title', $category->title);
    }
}