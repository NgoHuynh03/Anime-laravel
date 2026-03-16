<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Episode;
use App\Models\Category;
use App\Models\EpisodeServer; // Model quản lý link server
use Illuminate\Support\Str;

class AdminMovieController extends Controller
{
    // ====================================================
    // PHẦN 1: QUẢN LÝ PHIM (MOVIES)
    // ====================================================

    // 1. Danh sách phim (CÓ TÌM KIẾM)
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $query = Movie::orderBy('id', 'desc');

        // Nếu có từ khóa tìm kiếm
        if ($search) {
            $query->where('title', 'LIKE', "%{$search}%");
        }

        // Hiển thị 30 phim mỗi trang
        $movies = $query->paginate(30);

        return view('admin.movie_list', compact('movies', 'search'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.movie_create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|max:255',
            'thumb_url' => 'required|url',
            'total_episodes' => 'required|integer',
            'description' => 'nullable',
            'status' => 'required'
        ]);

        $data['slug'] = Str::slug($data['title']) . '-' . time();
        $movie = Movie::create($data);

        // Lưu thể loại
        if ($request->has('categories')) {
            $movie->categories()->attach($request->categories);
        }

        return redirect()->route('admin.movie.index')->with('success', 'Đã thêm phim thành công!');
    }

    public function edit($id)
    {
        $movie = Movie::findOrFail($id);
        $categories = Category::all();
        return view('admin.movie_edit', compact('movie', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $movie = Movie::findOrFail($id);

        $data = $request->validate([
            'title' => 'required|max:255',
            'thumb_url' => 'required|url',
            'total_episodes' => 'required|integer',
            'description' => 'nullable',
            'status' => 'required'
        ]);

        if ($movie->title != $data['title']) {
            $data['slug'] = Str::slug($data['title']) . '-' . time();
        }

        $movie->update($request->except('categories'));

        // Cập nhật thể loại
        if ($request->has('categories')) {
            $movie->categories()->sync($request->categories);
        } else {
            $movie->categories()->detach();
        }

        return redirect()->route('admin.movie.index')->with('success', 'Đã cập nhật phim thành công!');
    }

    public function destroy($id)
    {
        $movie = Movie::findOrFail($id);
        $movie->delete(); // Xóa phim -> Tự động xóa tập và server liên quan
        return redirect()->back()->with('success', 'Đã xóa phim: ' . $movie->title);
    }


    // ====================================================
    // PHẦN 2: QUẢN LÝ TẬP PHIM (EPISODES)
    // ====================================================

    public function listEpisodes($id)
    {
        $movie = Movie::findOrFail($id);
        // Lấy danh sách tập, kèm theo server để đếm số lượng
        $episodes = $movie->episodes()->with('servers')->orderBy('id', 'desc')->get();
        return view('admin.episode_list', compact('movie', 'episodes'));
    }

    // Form thêm tập (Đã update lấy danh sách Server cũ để gợi ý)
    public function createEpisode($id)
    {
        $movie = Movie::findOrFail($id);
        
        // MỚI: Lấy danh sách tên các Server đã từng tạo (Vip 1, Vip 2...) để làm gợi ý (datalist)
        $existingServers = EpisodeServer::select('server_name')
                            ->distinct()
                            ->pluck('server_name');

        return view('admin.episode_create', compact('movie', 'existingServers'));
    }

    // --- HÀM QUAN TRỌNG: LƯU TẬP (HỖ TRỢ UPSERT & BULK) ---
    public function storeEpisode(Request $request, $id)
    {
        $movie = Movie::findOrFail($id);
        
        // Lấy tên server từ form (Nếu người dùng không chọn/nhập thì mặc định là Server VIP)
        $serverName = $request->input('server_name', 'Server VIP');

        // Hàm helper nhỏ để xử lý URL (bọc iframe nếu là embed)
        $processUrl = function($url, $type) {
            $url = trim($url);
            if ($type == 'embed' && !Str::startsWith($url, '<iframe')) {
                return '<iframe src="'.$url.'" width="100%" height="500px" frameborder="0" allowfullscreen></iframe>';
            }
            return $url;
        };

        // ==========================================
        // TRƯỜNG HỢP 1: THÊM NHANH (BULK)
        // ==========================================
        if ($request->filled('bulk_episodes')) {
            $content = $request->bulk_episodes;
            $type = $request->bulk_type; // embed hoặc m3u8

            $lines = preg_split('/\r\n|\r|\n/', $content); 
            $countNew = 0;
            $countUpdate = 0;

            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line)) continue;

                $parts = explode('|', $line);
                if (count($parts) >= 2) {
                    $epName = trim($parts[0]);
                    $epRawUrl = trim($parts[1]);
                    $epSlug = Str::slug($epName);

                    $finalUrl = $processUrl($epRawUrl, $type);

                    // KIỂM TRA: Tập này đã có trong phim chưa?
                    $existingEpisode = $movie->episodes()->where('slug', $epSlug)->first();

                    if ($existingEpisode) {
                        // A. NẾU CÓ RỒI -> Thêm Server mới vào tập cũ
                        $existingEpisode->servers()->create([
                            'server_name' => $serverName,
                            'video_url' => $finalUrl,
                            'type' => $type
                        ]);
                        $countUpdate++;
                    } else {
                        // B. NẾU CHƯA CÓ -> Tạo tập mới + Server mới
                        $newEp = $movie->episodes()->create([
                            'name' => $epName,
                            'slug' => $epSlug,
                            'server_name' => $serverName
                        ]);

                        $newEp->servers()->create([
                            'server_name' => $serverName,
                            'video_url' => $finalUrl,
                            'type' => $type
                        ]);
                        $countNew++;
                    }
                }
            }

            return redirect()->route('admin.episode.index', $id)
                             ->with('success', "Xử lý xong: Đã tạo mới $countNew tập, Đã thêm server '$serverName' vào $countUpdate tập cũ.");
        }

        // ==========================================
        // TRƯỜNG HỢP 2: THÊM LẺ (SINGLE)
        // ==========================================
        $request->validate([
            'name' => 'required',
            'video_url' => 'required',
            'type' => 'required'
        ]);

        $epName = $request->name;
        $epSlug = Str::slug($epName);
        $finalUrl = $processUrl($request->video_url, $request->type);

        // Kiểm tra tồn tại
        $existingEpisode = $movie->episodes()->where('slug', $epSlug)->first();

        if ($existingEpisode) {
            // Nếu tập đã có -> Thêm Server mới
            $existingEpisode->servers()->create([
                'server_name' => $serverName,
                'video_url' => $finalUrl,
                'type' => $request->type
            ]);
            $msg = "Tập '$epName' đã tồn tại. Đã thêm '$serverName' vào danh sách server của tập này!";
        } else {
            // Nếu chưa có -> Tạo mới hoàn toàn
            $newEp = $movie->episodes()->create([
                'name' => $epName,
                'slug' => $epSlug,
                'server_name' => $serverName
            ]);
            
            $newEp->servers()->create([
                'server_name' => $serverName,
                'video_url' => $finalUrl,
                'type' => $request->type
            ]);
            $msg = "Đã tạo mới tập '$epName' với server '$serverName'!";
        }

        return redirect()->route('admin.episode.index', $id)->with('success', $msg);
    }

    public function editEpisode($id)
    {
        $episode = Episode::with('servers')->findOrFail($id);
        return view('admin.episode_edit', compact('episode'));
    }

    public function updateEpisode(Request $request, $id)
    {
        $episode = Episode::findOrFail($id);
        $request->validate(['name' => 'required']);
        
        $slug = Str::slug($request->name);
        if ($episode->slug != $slug) {
            $episode->slug = $slug;
        }
        $episode->name = $request->name;
        $episode->save();

        return redirect()->back()->with('success', 'Đã cập nhật tên tập phim!');
    }

    public function deleteEpisode($id)
    {
        $episode = Episode::findOrFail($id);
        $movieId = $episode->movie_id;
        $episode->delete(); // Xóa tập -> Xóa hết server con

        return redirect()->route('admin.episode.index', $movieId)
                         ->with('success', 'Đã xóa tập phim!');
    }


    // ====================================================
    // PHẦN 3: QUẢN LÝ SERVER (LINK DỰ PHÒNG)
    // ====================================================

    // Thêm Server mới vào một tập phim cụ thể (Từ trang Sửa Tập)
    public function storeServer(Request $request, $episode_id)
    {
        $episode = Episode::findOrFail($episode_id);

        $request->validate([
            'server_name' => 'required',
            'video_url' => 'required',
            'type' => 'required'
        ]);

        $videoUrl = $request->video_url;
        // Tự động bọc iframe nếu chọn embed
        if ($request->type == 'embed' && !Str::startsWith($videoUrl, '<iframe')) {
            $videoUrl = '<iframe src="'.$videoUrl.'" width="100%" height="500px" frameborder="0" allowfullscreen></iframe>';
        }

        $episode->servers()->create([
            'server_name' => $request->server_name,
            'video_url' => $videoUrl,
            'type' => $request->type
        ]);

        return redirect()->back()->with('success', 'Đã thêm Server mới thành công!');
    }

    // Xóa một Server cụ thể
    public function deleteServer($id)
    {
        $server = EpisodeServer::findOrFail($id);
        $server->delete();
        
        return redirect()->back()->with('success', 'Đã xóa server!');
    }
}