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
    Schema::create('movies', function (Blueprint $table) {
        $table->id(); // Tự động tạo ID tăng dần
        $table->string('title'); // Tên phim
        $table->string('slug')->unique(); // Đường dẫn đẹp (VD: dao-hai-tac), unique để không trùng
        $table->text('description')->nullable(); // Mô tả phim (nullable là cho phép để trống)
        $table->string('thumb_url')->nullable(); // Link ảnh poster
        $table->integer('total_episodes')->default(0); // Tổng số tập
        $table->string('status')->default('Đang tiến hành'); // Trạng thái
        $table->timestamps(); // Tự động tạo cột created_at (ngày tạo) và updated_at (ngày sửa)
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
