<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $genres = ['Hành Động', 'Tình Cảm', 'Hài Hước', 'Cổ Trang', 'Tâm Lý', 'Hình Sự', 'Chiến Tranh', 'Thể Thao', 'Võ Thuật', 'Viễn Tưởng', 'Kinh Dị'];

    foreach ($genres as $genre) {
        \App\Models\Category::create([
            'title' => $genre,
            'slug' => \Illuminate\Support\Str::slug($genre)
        ]);
    }
    }
}
