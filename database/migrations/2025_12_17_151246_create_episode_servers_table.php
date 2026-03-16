<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // 1. Tạo bảng mới
        Schema::create('episode_servers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('episode_id')->constrained('episodes')->onDelete('cascade');
            $table->string('server_name')->default('Server 1'); // Tên server (VIP, Dự phòng...)
            $table->text('video_url');
            $table->string('type')->default('embed'); // embed hoặc m3u8
            $table->timestamps();
        });

        // 2. CHUYỂN DỮ LIỆU CŨ SANG BẢNG MỚI (Quan trọng)
        // Lấy tất cả link từ bảng episodes cũ, ném sang bảng server mới
        $oldEpisodes = DB::table('episodes')->whereNotNull('video_url')->get();
        foreach ($oldEpisodes as $ep) {
            DB::table('episode_servers')->insert([
                'episode_id' => $ep->id,
                'server_name' => 'Server VIP',
                'video_url' => $ep->video_url,
                'type' => \Illuminate\Support\Str::contains($ep->video_url, '<iframe') ? 'embed' : 'm3u8',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('episode_servers');
    }
};