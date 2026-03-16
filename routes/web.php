<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\AdminMovieController;
use App\Http\Controllers\AdminCategoryController; // Controller quản lý thể loại
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| PHẦN 1: ROUTE CHO NGƯỜI XEM (Frontend - Ai cũng vào được)
|--------------------------------------------------------------------------
*/

// Trang chủ (kèm Slider & Tìm kiếm)
Route::get('/', [MovieController::class, 'index'])->name('home');

// Lọc phim theo thể loại (VD: domain.com/the-loai/hanh-dong)
Route::get('/the-loai/{slug}', [MovieController::class, 'listByCategory'])->name('category.movie');

// Trang chi tiết phim
Route::get('/phim/{slug}', [MovieController::class, 'show'])->name('movie.detail');

// Trang xem phim (Player)
Route::get('/xem-phim/{slug}/{episode_slug}', [MovieController::class, 'watch'])->name('movie.watch');


/*
|--------------------------------------------------------------------------
| PHẦN 2: ROUTE XÁC THỰC (Login / Logout)
|--------------------------------------------------------------------------
*/

// Hiển thị form login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
// Xử lý login
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
// Đăng xuất
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| PHẦN 3: ROUTE ADMIN (Backend - Phải đăng nhập mới vào được)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->middleware('auth')->group(function () {
    
    // ============================================
    // 1. QUẢN LÝ PHIM (Movies)
    // ============================================
    
    // Danh sách phim (Trang chủ Admin)
    Route::get('/', [AdminMovieController::class, 'index'])->name('admin.movie.index');
    
    // Thêm phim
    Route::get('/them-phim', [AdminMovieController::class, 'create'])->name('admin.movie.create');
    Route::post('/them-phim', [AdminMovieController::class, 'store'])->name('admin.movie.store');
    
    // Sửa phim
    Route::get('/phim/{id}/sua', [AdminMovieController::class, 'edit'])->name('admin.movie.edit');
    Route::put('/phim/{id}/sua', [AdminMovieController::class, 'update'])->name('admin.movie.update');
    
    // Xóa phim
    Route::delete('/phim/{id}/xoa', [AdminMovieController::class, 'destroy'])->name('admin.movie.destroy');


    // ============================================
    // 2. QUẢN LÝ TẬP PHIM (Episodes)
    // ============================================
    
    // Xem danh sách tập của 1 phim cụ thể
    Route::get('/phim/{id}/tap-phim', [AdminMovieController::class, 'listEpisodes'])->name('admin.episode.index');

    // Thêm tập cho phim
    Route::get('/phim/{id}/them-tap', [AdminMovieController::class, 'createEpisode'])->name('admin.episode.create');
    Route::post('/phim/{id}/them-tap', [AdminMovieController::class, 'storeEpisode'])->name('admin.episode.store');
    
    // Sửa tập phim
    Route::get('/tap-phim/{id}/sua', [AdminMovieController::class, 'editEpisode'])->name('admin.episode.edit');
    Route::put('/tap-phim/{id}/sua', [AdminMovieController::class, 'updateEpisode'])->name('admin.episode.update');

    // Xóa tập phim
    Route::delete('/tap-phim/{id}/xoa', [AdminMovieController::class, 'deleteEpisode'])->name('admin.episode.destroy');


    // ============================================
    // 3. QUẢN LÝ THỂ LOẠI (Categories) - MỚI
    // ============================================
    
    // Danh sách thể loại
    Route::get('/the-loai', [AdminCategoryController::class, 'index'])->name('admin.category.index');
    
    // Thêm thể loại
    Route::get('/the-loai/them', [AdminCategoryController::class, 'create'])->name('admin.category.create');
    Route::post('/the-loai/them', [AdminCategoryController::class, 'store'])->name('admin.category.store');
    
    // Xóa thể loại
    Route::delete('/the-loai/{id}/xoa', [AdminCategoryController::class, 'destroy'])->name('admin.category.destroy');

});

Route::post('/tap-phim/{id}/them-server', [AdminMovieController::class, 'storeServer'])->name('admin.server.store');
Route::get('/server/{id}/xoa', [AdminMovieController::class, 'deleteServer'])->name('admin.server.destroy');
// ... (Các route tập phim cũ giữ nguyên) ...

    // --- MỚI: QUẢN LÝ SERVER (LINK DỰ PHÒNG) ---
    // 1. Xử lý thêm server từ trang Sửa tập
    Route::post('/phim/tap/{id}/them-server', [AdminMovieController::class, 'storeServer'])->name('admin.server.store');
    
    // 2. Xóa server lẻ
    Route::get('/server/{id}/xoa', [AdminMovieController::class, 'deleteServer'])->name('admin.server.destroy');
    