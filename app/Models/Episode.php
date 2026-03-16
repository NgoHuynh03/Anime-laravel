<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    use HasFactory;

    // Các trường cho phép lưu
    protected $fillable = ['movie_id', 'name', 'slug', 'server_name'];

    // --- QUAN TRỌNG NHẤT: THÊM DÒNG NÀY ---
    // Khi thêm/sửa/xóa tập, nó sẽ cập nhật cột updated_at của bảng movies
    protected $touches = ['movie']; 

    public function movie()
    {
        return $this->belongsTo(Movie::class);
    }

    public function servers()
    {
        return $this->hasMany(EpisodeServer::class);
    }
}