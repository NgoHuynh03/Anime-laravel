<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('episodes', function (Blueprint $table) {
        $table->id();
        
        // Khóa ngoại: Liên kết với bảng movies
        // onDelete('cascade'): Nghĩa là nếu xóa phim, tất cả tập của phim đó tự xóa theo
        $table->foreignId('movie_id')->constrained('movies')->onDelete('cascade');
        
        $table->string('name'); // Tên tập (VD: Tập 1)
        $table->string('slug'); // Slug tập (VD: tap-1)
        $table->text('video_url'); // Link phim (iframe, mp4, m3u8...)
        $table->string('server_name')->default('VIP'); // Tên server (nếu web có nhiều server)
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('episodes');
    }
};
