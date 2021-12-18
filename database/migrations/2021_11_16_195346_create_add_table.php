<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table){
            $table->foreignId('country_id');
            $table->foreign('country_id')->references('id')->on('countries');
        });

        Schema::table('labels', function (Blueprint $table){
            $table->foreignId('author_id');
            $table->foreign('author_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table){
            $table->dropForeign('users_country_id_foreign');
            $table->dropColumn('country_id');
        });

        Schema::table('labels', function (Blueprint $table){
            $table->dropForeign('labels_author_id_foreign');
            $table->dropColumn('author_id');
        });
    }
}
