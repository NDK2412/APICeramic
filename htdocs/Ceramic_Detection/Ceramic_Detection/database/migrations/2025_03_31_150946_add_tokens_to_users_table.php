<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTokensToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('tokens')->default(10); // Thêm cột tokens, mặc định 10
        });

        Schema::table('users', function (Blueprint $table) {
            $table->integer('tokens')->default(10);
        });
        // Cập nhật token cho các user hiện có
        DB::table('users')->update(['tokens' => 10]);
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('tokens'); // Xóa cột nếu rollback
        });
    }
}