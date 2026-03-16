<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;

class AdminCategoryController extends Controller
{
    // 1. Danh sách thể loại
    public function index()
    {
        $categories = Category::all();
        return view('admin.category_list', compact('categories'));
    }

    // 2. Form thêm thể loại
    public function create()
    {
        return view('admin.category_create');
    }

    // 3. Lưu thể loại mới
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:categories|max:255', // Không được trùng tên
        ]);

        Category::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) // Tự tạo slug từ tên
        ]);

        return redirect()->route('admin.category.index')->with('success', 'Đã thêm thể loại mới!');
    }

    // 4. Xóa thể loại
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete(); // Xóa thể loại, các phim thuộc thể loại này sẽ tự động bỏ liên kết

        return redirect()->back()->with('success', 'Đã xóa thể loại: ' . $category->title);
    }
}