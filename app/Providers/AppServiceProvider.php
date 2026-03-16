<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator; // Để phân trang đẹp
use Illuminate\Support\Facades\View; // Để chia sẻ biến cho view
use App\Models\Category; // Gọi Model Thể loại

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // 1. Sử dụng phân trang Bootstrap 5
        Paginator::useBootstrapFive();

        // 2. Chia sẻ danh sách 'Thể loại' cho tất cả các trang (để làm Menu)
        // Dùng try-catch để tránh lỗi khi mới chạy migration chưa có bảng
        try {
            $categories = Category::all();
            View::share('global_categories', $categories);
        } catch (\Exception $e) {
            // Chưa có database thì bỏ qua
        }
    }
}