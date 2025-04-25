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
        Schema::table('settings', function (Blueprint $table) {
            $table->tinyInteger('recaptcha_enabled')->default(0)->after('value');
            // 0: Tắt, 1: Bật
        });
    }
    
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('recaptcha_enabled');
        });
    }
};
