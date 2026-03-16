<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    // Cho phép thêm dữ liệu nhanh vào các cột này
    protected $fillable = ['title', 'slug', 'description', 'thumb_url', 'total_episodes', 'status'];

    // Định nghĩa: 1 Phim có nhiều Tập
    public function episodes()
    {
        return $this->hasMany(Episode::class);
    }
    // Phim này thuộc nhiều thể loại
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_movie');
    }
}
