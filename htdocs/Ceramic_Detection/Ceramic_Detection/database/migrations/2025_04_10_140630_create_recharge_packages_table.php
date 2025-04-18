<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRechargePackagesTable extends Migration
{
    public function up()
    {
        Schema::create('recharge_packages', function (Blueprint $table) {
            $table->id();
            $table->integer('amount')->unique();
            $table->integer('tokens');
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Thêm dữ liệu mặc định (tùy chọn)
        \DB::table('recharge_packages')->insert([
            ['amount' => 50000, 'tokens' => 50, 'description' => 'Phù hợp cho người mới', 'is_active' => true],
            ['amount' => 100000, 'tokens' => 110, 'description' => 'Tiết kiệm nhất', 'is_active' => true],
            ['amount' => 200000, 'tokens' => 240, 'description' => 'Dành cho người dùng thường xuyên', 'is_active' => true],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('recharge_packages');
    }
}