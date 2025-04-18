<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassificationsTable extends Migration
{
    public function up()
    {
        Schema::create('classifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('image_path');
            $table->string('result');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('classifications');
    }
}