<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLlmResponseToClassificationsTable extends Migration
{
    public function up()
    {
        Schema::table('classifications', function (Blueprint $table) {
            $table->text('llm_response')->nullable()->after('result');
        });
    }

    public function down()
    {
        Schema::table('classifications', function (Blueprint $table) {
            $table->dropColumn('llm_response');
        });
    }
}