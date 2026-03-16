<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller
{
    // 1. Hiện form đăng nhập
    public function showLogin() {
        return view('login');
    }

    // 2. Xử lý đăng nhập
    public function login(Request $request) {
        // Lấy thông tin email và pass từ form
        $credentials = $request->only('email', 'password');

        // Auth::attempt sẽ tự kiểm tra xem đúng email/pass trong database không
        if (Auth::attempt($credentials)) {
            // Nếu đúng -> Chuyển hướng vào trang Admin
            return redirect()->route('admin.movie.index');
        }

        // Nếu sai -> Quay lại form login và báo lỗi
        return back()->withErrors(['msg' => 'Email hoặc mật khẩu không đúng!']);
    }

    // 3. Đăng xuất
    public function logout() {
        Auth::logout();
        return redirect()->route('login'); // Quay về trang login
    }
}
