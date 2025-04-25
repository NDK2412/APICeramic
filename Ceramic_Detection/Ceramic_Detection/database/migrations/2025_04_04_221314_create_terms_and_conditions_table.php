<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTermsAndConditionsTable extends Migration
{
    public function up()
    {
        Schema::create('terms_and_conditions', function (Blueprint $table) {
            $table->id();
            $table->text('content')->nullable();
            $table->timestamps();
        });

        // Thêm dữ liệu mặc định
        DB::table('terms_and_conditions')->insert([
            'content' => 'Đây là chính sách và điều khoản mặc định. Vui lòng đọc kỹ trước khi đăng ký. Bạn cần đồng ý với các điều khoản này để sử dụng dịch vụ của chúng tôi.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('terms_and_conditions');
    }
}