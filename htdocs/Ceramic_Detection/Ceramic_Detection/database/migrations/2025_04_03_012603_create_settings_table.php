<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Cột để lưu key (ví dụ: 'timezone')
            $table->string('value')->nullable(); // Cột để lưu giá trị (ví dụ: 'Asia/Ho_Chi_Minh')
            $table->timestamps(); // Cột created_at và updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
}