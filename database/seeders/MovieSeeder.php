<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
public function run(): void
{
    // Tạo phim One Piece
    $movie = \App\Models\Movie::create([
        'title' => 'Đảo Hải Tặc (One Piece)',
        'slug'  => 'dao-hai-tac',
        'description' => 'Phim về hành trình tìm kho báu của Luffy.',
        'thumb_url'   => 'https://i.pinimg.com/736x/ea/16/e0/ea16e033bb86082f42a5925a65345758.jpg',
        'total_episodes' => 1000,
        'status' => 'Đang chiếu'
    ]);

    // Tạo tập 1 cho One Piece
    $movie->episodes()->create([
        'name' => 'Tập 1: Tôi là Luffy',
        'slug' => 'tap-1',
        'video_url' => 'https://www.youtube.com/embed/AQA2T7F14t4', // Link youtube test cho lẹ
        'server_name' => 'VIP'
    ]);

    // Tạo thêm 1 phim nữa: Naruto
    $movie2 = \App\Models\Movie::create([
        'title' => 'Naruto Shippuden',
        'slug'  => 'naruto-shippuden',
        'description' => 'Huyền thoại Ninja.',
        'thumb_url'   => 'https://m.media-amazon.com/images/M/MV5BZmQ5NGFiNWEtMmMyMC00MDdiLTg4YjktOGY5Yzc2MDUxMTE1XkEyXkFqcGdeQXVyNTA4NzY1MzY@._V1_FMjpg_UX1000_.jpg',
        'total_episodes' => 500,
        'status' => 'Hoàn thành'
    ]);
    
    // Tạo tập 1 cho Naruto
    $movie2->episodes()->create([
        'name' => 'Tập 1: Trở về',
        'slug' => 'tap-1',
        'video_url' => 'https://www.youtube.com/embed/QCZ8lIcdiHU',
        'server_name' => 'VIP'
    ]);
}
}
