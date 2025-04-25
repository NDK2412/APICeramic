<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApiTokenToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('api_token', 80)->nullable()->unique()->after('password');
            $table->boolean('active')->default(true)->after('api_token');
            $table->string('role')->default('user')->after('active');
            $table->integer('tokens')->default(100)->after('role');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['api_token', 'active', 'role', 'tokens']);
        });
    }
}