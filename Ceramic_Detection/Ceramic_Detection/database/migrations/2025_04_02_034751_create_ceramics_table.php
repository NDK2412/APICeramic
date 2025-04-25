<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ceramics', function (Blueprint $table) {
            $table->id(); // Tạo cột id tự động tăng
            $table->string('name'); // Tạo cột name (tên món đồ gốm)
            $table->text('description')->nullable(); // Tạo cột description (mô tả), có thể để trống
            $table->string('image')->nullable(); // Tạo cột image (hình ảnh), có thể để trống
            $table->string('category')->nullable(); // Tạo cột category (danh mục), có thể để trống
            $table->string('origin')->nullable(); // Tạo cột origin (nguồn gốc), có thể để trống
            $table->timestamps(); // Tạo cột created_at và updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ceramics'); // Xóa bảng nếu cần rollback
    }
};